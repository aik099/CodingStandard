<?php
/**
 * CodingStandard_Sniffs_Strings_ConcatenationSpacingSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * CodingStandard_Sniffs_Strings_ConcatenationSpacingSniff.
 *
 * Makes sure there are the needed spaces between the concatenation operator (.) and
 * the strings being concatenated.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Strings_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_STRING_CONCAT);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if (isset($phpcsFile->fixerWrapper) === false) {
            $phpcsFile->fixerWrapper = CodingStandard_Sniffs_FixerWrapper_WrapperFactory::createWrapper($phpcsFile);
        }

        $tokens = $phpcsFile->getTokens();

        $found       = '';
        $expected    = '';
        $errorBefore = false;
        $errorAfter  = false;

        $concatOperator = $tokens[$stackPtr]['content'];
        if ($tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
            $whitespaceContent = $tokens[($stackPtr - 1)]['content'];
            $beforeContent     = $this->getBeforeContent($phpcsFile, ($stackPtr - 2));
            $found            .= $beforeContent.$whitespaceContent.$concatOperator;
            $expected         .= $beforeContent.$whitespaceContent.$concatOperator;
        } else {
            // No whitespace before concat operator.
            $errorBefore   = true;
            $beforeContent = $this->getBeforeContent($phpcsFile, ($stackPtr - 1));
            $expected     .= $beforeContent.' '.$concatOperator;
            $found        .= $beforeContent.$concatOperator;
        }

        if ($tokens[($stackPtr + 1)]['code'] === T_WHITESPACE) {
            $whitespaceContent = $tokens[($stackPtr + 1)]['content'];
            $afterContent      = $this->getAfterContent($phpcsFile, ($stackPtr + 2));
            $found            .= $whitespaceContent.$afterContent;
            $expected         .= $whitespaceContent.$afterContent;
        } else {
            // No whitespace after concat operator.
            $errorAfter   = true;
            $afterContent = $this->getAfterContent($phpcsFile, ($stackPtr + 1));
            $expected    .= ' '.$afterContent;
            $found       .= $afterContent;
        }

        if ($errorBefore === true || $errorAfter === true) {
            $found    = str_replace("\r\n", '\n', $found);
            $found    = str_replace("\n", '\n', $found);
            $found    = str_replace("\r", '\n', $found);
            $expected = str_replace("\r\n", '\n', $expected);
            $expected = str_replace("\n", '\n', $expected);
            $expected = str_replace("\r", '\n', $expected);

            $message = "Concat operator must be surrounded by spaces. Found \"$found\"; expected \"$expected\"";
            $fix     = $phpcsFile->fixerWrapper->addFixableError($message, $stackPtr);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                if ($errorBefore === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
                }

                if ($errorAfter === true) {
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end process()


    /**
     * Returns content (given stack pointer) shortened from the start.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return string
     */
    protected function getBeforeContent(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        return '...'.substr($tokens[$stackPtr]['content'], -5);

    }//end getBeforeContent()


    /**
     * Returns content (given stack pointer) shortened from the end.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return string
     */
    protected function getAfterContent(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        return substr($tokens[$stackPtr]['content'], 0, 5).'...';

    }//end getAfterContent()


}//end class

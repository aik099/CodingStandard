<?php
/**
 * CodingStandard_Sniffs_Formatting_ItemAssignmentSniff.
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
 * CodingStandard_Sniffs_Formatting_ItemAssignmentSniff.
 *
 * Checks if the item assignment operator (=>) has
 * - a space before and after
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Formatting_ItemAssignmentSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_DOUBLE_ARROW);

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
        $this->checkSpacing($phpcsFile, $stackPtr, true);
        $this->checkSpacing($phpcsFile, $stackPtr, false);

    }//end process()


    /**
     * Checks spacing at given position.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     * @param bool                 $before    Determines direction in which to check spacing.
     *
     * @return void
     */
    protected function checkSpacing(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $before)
    {
        if ($before === true) {
            $stackPtrDiff = -1;
            $errorWord    = 'prefix';
            $errorCode    = 'Before';
        } else {
            $stackPtrDiff = 1;
            $errorWord    = 'follow';
            $errorCode    = 'After';
        }

        $tokens    = $phpcsFile->getTokens();
        $tokenData = $tokens[($stackPtr + $stackPtrDiff)];

        if ($tokenData['code'] !== T_WHITESPACE) {
            $error = 'Whitespace must '.$errorWord.' the item assignment operator =>';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpacing'.$errorCode);
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                if ($before === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
                } else {
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                }

                $phpcsFile->fixer->endChangeset();
            }

            return;
        }

        if ($this->hasOnlySpaces($tokenData['content']) === false) {
            $error = 'Spaces must be used to '.$errorWord.' the item assignment operator =>';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'MixedWhitespace'.$errorCode);
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken(
                    ($stackPtr + $stackPtrDiff),
                    str_repeat(' ', $tokenData['length'])
                );
                $phpcsFile->fixer->endChangeset();
            }
        }

    }//end checkSpacing()


    /**
     * Detects, that string contains only spaces.
     *
     * @param string $string String.
     *
     * @return bool
     */
    protected function hasOnlySpaces($string)
    {
        return substr_count($string, ' ') === strlen($string);

    }//end hasOnlySpaces()


}//end class

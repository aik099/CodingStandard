<?php
/**
 * CodingStandard_Sniffs_CodeAnalysis_WrongParentCallSniff.
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
 * CodingStandard_Sniffs_CodeAnalysis_WrongParentCallSniff.
 *
 * Checks that method is invoking it's own parent and not other function.
 *
 * Correct:
 * function nameOne() {
 *     parent::nameOne();
 *
 *     ....
 * }
 *
 * Wrong:
 * function nameOne() {
 *     parent::nameTwo();
 *
 *     ....
 * }
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_CodeAnalysis_WrongParentCallSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_PARENT);

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
        $functionPtr = $phpcsFile->findPrevious(T_FUNCTION, ($stackPtr - 1));

        if ($functionPtr !== false) {
            $doubleColonPtr = $phpcsFile->findNext(T_DOUBLE_COLON, ($stackPtr + 1));

            if ($doubleColonPtr !== false) {
                $tokens     = $phpcsFile->getTokens();
                $expression = $phpcsFile->getTokensAsString($stackPtr, (($doubleColonPtr - $stackPtr) + 1));

                if ($expression !== 'parent::') {
                    // Class constant named "PARENT".
                    return;
                }

                $functionName  = $phpcsFile->getDeclarationName($functionPtr);
                $methodNamePtr = $phpcsFile->findNext(T_STRING, ($stackPtr + 1));

                if ($methodNamePtr !== false && $tokens[$methodNamePtr]['content'] !== $functionName) {
                    $error = 'Method name mismatch in parent:: call';
                    $phpcsFile->addError($error, $stackPtr, 'WrongName');
                }
            }
        }//end if

    }//end process()


}//end class

?>

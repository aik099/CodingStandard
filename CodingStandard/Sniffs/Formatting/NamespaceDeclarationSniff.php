<?php
/**
 * CodingStandard_Sniffs_Formatting_NamespaceDeclarationSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * Namespace formatting test.
 *
 * Checks the that of empty lines present after namespace.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Formatting_NamespaceDeclarationSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * Empty line count after namespace declaration.
     *
     * @var int
     */
    public $emptyLineCount = 2;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_NAMESPACE);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param integer              $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // The "4" offset is: whitespace + namespace name + semicolon.
        $nextCodePtr = $phpcsFile->findNext(
            T_WHITESPACE,
            ($stackPtr + 4),
            null,
            true
        );

        if ($nextCodePtr !== false
            && ($tokens[$nextCodePtr]['line'] - $this->emptyLineCount) !== ($tokens[$stackPtr]['line'] + 1)
        ) {
            $data  = array(
                      ($tokens[$nextCodePtr]['line'] - ($tokens[$stackPtr]['line'] + 1)),
                     );
            $error = 'Expected '.$this->emptyLineCount.' blank lines after namespace declaration; %s found';
            $phpcsFile->addError($error, $stackPtr, 'LineAfter', $data);
        }

    }//end process()


}//end class

?>

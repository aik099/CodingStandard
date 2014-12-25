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
     * @return integer[]
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
        $tokens            = $phpcsFile->getTokens();
        $declarationEndPtr = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr + 1));

        $nextCodePtr = $phpcsFile->findNext(
            T_WHITESPACE,
            ($declarationEndPtr + 1),
            null,
            true
        );

        if ($nextCodePtr === false) {
            return;
        }

        $diff = ($tokens[$nextCodePtr]['line'] - $tokens[$declarationEndPtr]['line'] - 1);

        if ($diff === $this->emptyLineCount) {
            return;
        }

        if ($diff < 0) {
            $diff = 0;
        }

        $data  = array($diff);
        $error = 'Expected '.$this->emptyLineCount.' blank line(-s) after namespace declaration; %s found';
        $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'BlankLineAfter', $data);

        if ($fix === false) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();

        if ($diff > 0) {
            for ($j = ($declarationEndPtr + 1); $j < $nextCodePtr; $j++) {
                if ($tokens[$j]['line'] === $tokens[$nextCodePtr]['line']) {
                    // Keep existing indentation.
                    break;
                }

                $phpcsFile->fixer->replaceToken($j, '');
            }
        }

        for ($i = 0; $i <= $this->emptyLineCount; $i++) {
            $phpcsFile->fixer->addNewline($declarationEndPtr);
        }

        $phpcsFile->fixer->endChangeset();

    }//end process()


}//end class

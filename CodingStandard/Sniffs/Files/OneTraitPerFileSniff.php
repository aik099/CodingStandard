<?php
/**
 * CodingStandard_Sniffs_Files_OneTraitPerFileSniff.
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
 * Checks that only one trait is declared per file.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Files_OneTraitPerFileSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_TRAIT);

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $nextClass = $phpcsFile->findNext($this->register(), ($stackPtr + 1));
        if ($nextClass !== false) {
            $error = 'Only one trait is allowed in a file';
            $phpcsFile->addError($error, $nextClass, 'MultipleFound');
        }

    }//end process()


}//end class

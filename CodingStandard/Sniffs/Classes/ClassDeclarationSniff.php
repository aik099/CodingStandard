<?php
/**
 * CodingStandard_Sniffs_Classes_ClassDeclarationSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\PSR2\Sniffs\Classes\ClassDeclarationSniff as PSR2_ClassDeclarationSniff;

/**
 * Class Declaration Test.
 *
 * Checks the declaration of the class and its inheritance is correct.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class ClassDeclarationSniff extends PSR2_ClassDeclarationSniff
{


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        // We want all the errors from the PEAR standard, plus some of our own.
        parent::process($phpcsFile, $stackPtr);
    }//end process()


    /**
     * Processes the opening section of a class declaration.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processOpen(File $phpcsFile, $stackPtr)
    {
        parent::processOpen($phpcsFile, $stackPtr);

        $tokens = $phpcsFile->getTokens();

        if ($tokens[($stackPtr - 1)]['code'] === T_WHITESPACE) {
            $prevContent = $tokens[($stackPtr - 1)]['content'];
            if ($prevContent !== $phpcsFile->eolChar) {
                $blankSpace = substr($prevContent, strpos($prevContent, $phpcsFile->eolChar));
                $spaces     = strlen($blankSpace);

                if (in_array($tokens[($stackPtr - 2)]['code'], array(T_ABSTRACT, T_FINAL)) === false) {
                    if ($spaces !== 0) {
                        $type  = strtolower($tokens[$stackPtr]['content']);
                        $error = 'Expected 0 spaces before %s keyword; %s found';
                        $data  = array(
                                  $type,
                                  $spaces,
                                 );

                        $fix = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceBeforeKeyword', $data);
                        if ($fix === true) {
                            $phpcsFile->fixer->beginChangeset();
                            $phpcsFile->fixer->replaceToken(($stackPtr - 1), '');
                            $phpcsFile->fixer->endChangeset();
                        }
                    }
                }
            }//end if
        }//end if
    }//end processOpen()


    /**
     * Processes the closing section of a class declaration.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processClose(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Just in case.
        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $closeBrace = $tokens[$stackPtr]['scope_closer'];
        if ($tokens[($closeBrace - 1)]['code'] === T_WHITESPACE) {
            $prevContent = $tokens[($closeBrace - 1)]['content'];
            if ($prevContent !== $phpcsFile->eolChar) {
                $blankSpace = substr($prevContent, strpos($prevContent, $phpcsFile->eolChar));
                $spaces     = strlen($blankSpace);
                if ($spaces !== 0) {
                    if ($tokens[($closeBrace - 1)]['line'] !== $tokens[$closeBrace]['line']) {
                        $error = 'Expected 0 spaces before closing brace; newline found';
                        $fix   = $phpcsFile->addFixableError($error, $closeBrace, 'NewLineBeforeCloseBrace');
                    } else {
                        $error = 'Expected 0 spaces before closing brace; %s found';
                        $data  = array($spaces);
                        $fix   = $phpcsFile->addFixableError($error, $closeBrace, 'SpaceBeforeCloseBrace', $data);
                    }//end if

                    if ($fix === true) {
                        $phpcsFile->fixer->beginChangeset();
                        $phpcsFile->fixer->replaceToken(($closeBrace - 1), '');
                        $phpcsFile->fixer->endChangeset();
                    }
                }//end if
            }//end if
        }//end if

        // Check that the closing brace has one blank line after it.
        $nextContent = $phpcsFile->findNext(array(T_WHITESPACE), ($closeBrace + 1), null, true);
        if ($nextContent !== false) {
            $difference = ($tokens[$nextContent]['line'] - $tokens[$closeBrace]['line'] - 1);
            if ($difference < 0) {
                $difference = 0;
            }

            if ($difference !== 1) {
                $error = 'Closing brace of a %s must be followed by a single blank line; found %s';
                $data  = array(
                          $tokens[$stackPtr]['content'],
                          $difference,
                         );
                $fix   = $phpcsFile->addFixableError($error, $closeBrace, 'NewlinesAfterCloseBrace', $data);
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    if ($difference > 1) {
                        for ($i = ($closeBrace + 1); $i < $nextContent; $i++) {
                            if ($tokens[$i]['line'] === $tokens[$nextContent]['line']) {
                                // Keep existing indentation.
                                break;
                            }

                            $phpcsFile->fixer->replaceToken($i, '');
                        }
                    }

                    $phpcsFile->fixer->addNewline($closeBrace);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        }//end if
    }//end processClose()
}//end class

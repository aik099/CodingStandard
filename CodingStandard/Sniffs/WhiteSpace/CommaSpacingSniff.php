<?php
/**
 * CodingStandard_Sniffs_WhiteSpace_CommaSpacingSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * CodingStandard_Sniffs_WhiteSpace_CommaSpacingSniff.
 *
 * Ensure there is single whitespace after comma and none before.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CommaSpacingSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_COMMA);
    }//end register()


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
        $this->checkContentBefore($phpcsFile, $stackPtr);
        $this->checkContentAfter($phpcsFile, $stackPtr);
    }//end process()

    /**
     * Checks spacing before comma.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function checkContentBefore(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $tokens[($stackPtr - 1)];

        if ($prevToken['content'] === '(') {
            return;
        }

        if ($prevToken['code'] === T_WHITESPACE && $tokens[($stackPtr - 2)]['code'] !== T_COMMA) {
            $error = 'Space found before comma';
            $fix = $phpcsFile->addFixableError($error, $stackPtr, 'Before');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($stackPtr - 1), '');
            }
        }
    }//end checkContentBefore()

    /**
     * Checks spacing after comma.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function checkContentAfter(File $phpcsFile, $stackPtr)
    {
        $tokens    = $phpcsFile->getTokens();
        $nextToken = $tokens[($stackPtr + 1)];

        if ($nextToken['content'] === ')') {
            return;
        }

        if ($nextToken['code'] !== T_WHITESPACE) {
            $error = 'No space found after comma';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'After');
            if ($fix === true) {
                $phpcsFile->fixer->addContent($stackPtr, ' ');
            }
        } elseif ($nextToken['content'] !== $phpcsFile->eolChar) {
            $spacingLength = $nextToken['length'];
            if ($spacingLength === 1) {
                $tokenAfterSpace = $tokens[($stackPtr + 2)];
                if ($tokenAfterSpace['content'] === ')') {
                    $error = 'Space found after comma';
                    $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'After');
                    if ($fix === true) {
                        $phpcsFile->fixer->replaceToken(($stackPtr + 1), '');
                    }
                }
            } else {
                $error = 'Expected 1 space after comma; %s found';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'After', array($spacingLength));
                if ($fix === true) {
                    $phpcsFile->fixer->replaceToken(($stackPtr + 1), ' ');
                }
            }
        }
    }//end checkContentAfter()
}//end class

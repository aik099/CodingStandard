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

namespace CodingStandard\Sniffs\Strings;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

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
class ConcatenationSpacingSniff implements Sniff
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $this->checkContent($phpcsFile, $stackPtr, true);
        $this->checkContent($phpcsFile, $stackPtr, false);
    }//end process()


    /**
     * Checks content before concat operator.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     * @param bool $before    Check content before concat operator.
     *
     * @return void
     */
    protected function checkContent(File $phpcsFile, $stackPtr, $before)
    {
        if ($before === true) {
            $contentToken = ($phpcsFile->findPrevious(
                T_WHITESPACE,
                ($stackPtr - 1),
                null,
                true
            ) + 1);
            $errorWord    = 'before';
        } else {
            $contentToken = ($phpcsFile->findNext(
                T_WHITESPACE,
                ($stackPtr + 1),
                null,
                true
            ) - 1);
            $errorWord    = 'after';
        }

        $tokens      = $phpcsFile->getTokens();
        $contentData = $tokens[$contentToken];

        if ($contentData['line'] !== $tokens[$stackPtr]['line']) {
            // Ignore concat operator split across several lines.
            return;
        }

        if ($contentData['code'] !== T_WHITESPACE) {
            $fix = $phpcsFile->addFixableError(
                'Expected 1 space '.$errorWord.' concat operator; 0 found',
                $stackPtr,
                'NoSpace'.ucfirst($errorWord)
            );

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                if ($before === true) {
                    $phpcsFile->fixer->addContentBefore($stackPtr, ' ');
                } else {
                    $phpcsFile->fixer->addContent($stackPtr, ' ');
                }

                $phpcsFile->fixer->endChangeset();
            }
        } elseif ($contentData['length'] !== 1) {
            $data = array($contentData['length']);
            $fix  = $phpcsFile->addFixableError(
                'Expected 1 space '.$errorWord.' concat operator; %s found',
                $stackPtr,
                'SpaceBefore'.ucfirst($errorWord),
                $data
            );

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken($contentToken, ' ');
                $phpcsFile->fixer->endChangeset();
            }
        }
    }//end checkContent()
}//end class

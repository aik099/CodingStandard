<?php
/**
 * CodingStandard_Sniffs_Formatting_SpaceOperatorSniff.
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

namespace CodingStandard\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * CodingStandard_Sniffs_Formatting_SpaceOperatorSniff.
 *
 * Ensures there is a single space after a operator
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class SpaceOperatorSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
         return Tokens::$assignmentTokens;

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
        // Only cover places, that are not handled by "Squiz.WhiteSpace.OperatorSpacing" sniff.
        $tokens = $phpcsFile->getTokens();

        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE) {
            return;
        }

        if ($tokens[($stackPtr - 2)]['line'] !== $tokens[$stackPtr]['line']) {
            $found = 'newline';
        } else {
            $found = $tokens[($stackPtr - 1)]['length'];
        }

        if (isset($phpcsFile->fixer) === true) {
            $phpcsFile->recordMetric($stackPtr, 'Space before operator', $found);
        }

        if ($found !== 1) {
            $error = 'Expected 1 space before "%s"; %s found';
            $data  = array(
                      $tokens[$stackPtr]['content'],
                      $found,
                     );
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpacingBefore', $data);

            if ($fix === true) {
                $phpcsFile->fixer->replaceToken(($stackPtr - 1), ' ');
            }
        }

    }//end process()


}//end class

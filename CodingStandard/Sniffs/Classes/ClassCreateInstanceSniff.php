<?php
/**
 * CodingStandard_Sniffs_Classes_ClassCreateInstanceSniff.
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

namespace CodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Class create instance Test.
 *
 * Checks the declaration of the class is correct.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class ClassCreateInstanceSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_NEW);
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
        $scopeEnd = null;
        $tokens   = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['nested_parenthesis']) === true) {
            // New in PHP 5.4: allow to instantiate class and immediately call method on it.
            list (, $scopeEnd) = each($tokens[$stackPtr]['nested_parenthesis']);
        }

        $nextParenthesis = $phpcsFile->findNext(
            T_OPEN_PARENTHESIS,
            ($stackPtr + 1),
            $scopeEnd,
            false,
            null,
            true
        );

        if ($nextParenthesis === false || $tokens[$nextParenthesis]['line'] !== $tokens[$stackPtr]['line']) {
            $error = 'Calling class constructors must always include parentheses';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'MissingParenthesis');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $classNameEnd = $phpcsFile->findNext(
                    array(
                     T_WHITESPACE,
                     T_NS_SEPARATOR,
                     T_STRING,
                    ),
                    ($stackPtr + 1),
                    null,
                    true,
                    null,
                    true
                );

                $phpcsFile->fixer->addContentBefore($classNameEnd, '()');
                $phpcsFile->fixer->endChangeset();
            }//end if
        } elseif ($tokens[($nextParenthesis - 1)]['code'] === T_WHITESPACE) {
            $error = 'Between the class name and the opening parenthesis spaces are not welcome';
            $fix   = $phpcsFile->addFixableError($error, ($nextParenthesis - 1), 'ExtraSpaces');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->replaceToken(($nextParenthesis - 1), '');
                $phpcsFile->fixer->endChangeset();
            }//end if
        }//end if
    }//end process()
}//end class

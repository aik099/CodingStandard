<?php
/**
 * CodingStandard_Sniffs_ControlStructures_AssignmentInConditionSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Checks that assignments in conditions are not used.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class AssignmentInConditionSniff implements Sniff
{
    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_IF,
                T_ELSEIF,
               );
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
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener']) === false) {
            return;
        }

        $equalOperator = $phpcsFile->findNext(
            T_EQUAL,
            $tokens[$stackPtr]['parenthesis_opener'],
            $tokens[$stackPtr]['parenthesis_closer']
        );

        if ($equalOperator === false) {
            return;
        }

        $phpcsFile->addError(
            'Assignment in "'.$tokens[$stackPtr]['content'].'" control structure is forbidden',
            $equalOperator,
            'Forbidden'
        );
    }//end process()
}//end class

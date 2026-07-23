<?php
/**
 * CodingStandard_Sniffs_CodeAnalysis_FunctionParameterAssignmentSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\CodeAnalysis;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * CodingStandard_Sniffs_CodeAnalysis_FunctionParameterAssignmentSniff.
 *
 * Checks that function/method parameters, that aren't passed by reference, don't have their value
 * overwritten within the function body. Code that reads the parameter later expects to see the
 * original value and not some other value, that was assigned to it during method execution.
 *
 * Correct:
 * function functionName($param1, &$param2)
 * {
 *     $param2 = 'new value';
 * }
 *
 * Wrong:
 * function functionName($param1, &$param2)
 * {
 *     $param1 = 'new value' . $param1;
 * }
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class FunctionParameterAssignmentSniff implements Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_FUNCTION);
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

        if (isset($tokens[$stackPtr]['scope_opener'], $tokens[$stackPtr]['scope_closer']) === false) {
            // Function declaration without a body (e.g. abstract method or interface method).
            return;
        }

        $parameters = $phpcsFile->getMethodParameters($stackPtr);

        if (empty($parameters) === true) {
            return;
        }

        $scopeOpener = $tokens[$stackPtr]['scope_opener'];
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        foreach ($parameters as $parameter) {
            if ($parameter['pass_by_reference'] === true) {
                continue;
            }

            $this->checkParameterUsage($phpcsFile, $parameter['name'], $scopeOpener, $scopeCloser);
        }
    }//end process()


    /**
     * Checks, that given parameter isn't assigned a new value within the given scope.
     *
     * @param File   $phpcsFile    The file being scanned.
     * @param string $variableName Name of the variable (including "$") to look for.
     * @param int    $scopeOpener  Position of the function body scope opener.
     * @param int    $scopeCloser  Position of the function body scope closer.
     *
     * @return void
     */
    protected function checkParameterUsage(File $phpcsFile, $variableName, $scopeOpener, $scopeCloser)
    {
        $tokens = $phpcsFile->getTokens();

        $searchPtr = $scopeOpener;

        do {
            $variablePtr = $phpcsFile->findNext(T_VARIABLE, ($searchPtr + 1), $scopeCloser, false, $variableName);

            if ($variablePtr === false) {
                break;
            }

            $searchPtr = $variablePtr;

            $assignmentPtr = $phpcsFile->findNext(Tokens::$emptyTokens, ($variablePtr + 1), null, true);

            if ($assignmentPtr !== false
                && isset(Tokens::$assignmentTokens[$tokens[$assignmentPtr]['code']]) === true
            ) {
                $warning = 'Assignment to "%s" function parameter is not allowed';
                $phpcsFile->addWarning($warning, $variablePtr, 'NotAllowed', array($variableName));
            }
        } while (true);
    }//end checkParameterUsage()
}//end class

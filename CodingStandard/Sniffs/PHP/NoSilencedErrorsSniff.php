<?php
/**
 * CodingStandard_Sniffs_PHP_NoSilencedErrorsSniffSniff
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

// @codeCoverageIgnoreStart
if (class_exists('Generic_Sniffs_PHP_NoSilencedErrorsSniff', true) === false) {
    $error = 'Class Generic_Sniffs_PHP_NoSilencedErrorsSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * CodingStandard_Sniffs_PHP_NoSilencedErrorsSniffSniff.
 *
 * Throws an error or warning when any code prefixed with an asperand is encountered.
 *
 * <code>
 *  if (@in_array($array, $needle))
 *  {
 *      doSomething();
 *  }
 * </code>
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_PHP_NoSilencedErrorsSniff extends Generic_Sniffs_PHP_NoSilencedErrorsSniff
{

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $secondTokenData = $tokens[($stackPtr + 1)];
        $thirdTokenData = $tokens[($stackPtr + 2)];

        // This is a silenced "trigger_error" function call.
        if ($secondTokenData['code'] === T_STRING
            && $secondTokenData['content'] === 'trigger_error'
            && $thirdTokenData['code'] === T_OPEN_PARENTHESIS
            && isset($thirdTokenData['parenthesis_closer']) === true
        ) {
            $lastArgumentToken = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($thirdTokenData['parenthesis_closer'] - 1),
                ($thirdTokenData['parenthesis_opener'] + 1),
                true
            );

            $lastArgumentTokenData = $tokens[$lastArgumentToken];

            if ($lastArgumentTokenData['code'] === T_STRING
                && $lastArgumentTokenData['content'] === 'E_USER_DEPRECATED'
            ) {
                return;
            }
        }

        parent::process($phpcsFile, $stackPtr);

    }//end process()


}//end class

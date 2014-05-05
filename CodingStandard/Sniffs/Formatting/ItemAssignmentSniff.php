<?php
/**
 * CodingStandard_Sniffs_Formatting_ItemAssignmentSniff.
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

/**
 * CodingStandard_Sniffs_Formatting_ItemAssignmentSniff.
 *
 * Checks if the item assignment operator (=>) has
 * - a space before and after
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Formatting_ItemAssignmentSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_DOUBLE_ARROW);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens          = $phpcsFile->getTokens();
        $tokenBeforeData = $tokens[($stackPtr - 1)];
        $tokenAfterData  = $tokens[($stackPtr + 1)];

        if ($tokenBeforeData['code'] !== T_WHITESPACE) {
            $phpcsFile->addError('Whitespace must prefix the item assignment operator =>', $stackPtr);
        } else if ($this->hasOnlySpaces($tokenBeforeData['content']) === false) {
            $phpcsFile->addError('Spaces must be used to prefix the item assignment operator =>', $stackPtr);
        }

        if ($tokenAfterData['code'] !== T_WHITESPACE) {
            $phpcsFile->addError('Whitespace must follow the item assignment operator =>', $stackPtr);
        } else if ($this->hasOnlySpaces($tokenAfterData['content']) === false) {
            $phpcsFile->addError('Spaces must be used to follow the item assignment operator =>', $stackPtr);
        }

    }//end process()


    /**
     * Detects, that string contains only spaces.
     *
     * @param string $string String.
     *
     * @return bool
     */
    protected function hasOnlySpaces($string)
    {
        return substr_count($string, ' ') === strlen($string);

    }//end hasOnlySpaces()


}//end class

?>

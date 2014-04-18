<?php

/**
 * CodingStandard_Sniffs_Formatting_BlankLineBeforeReturnSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Symfony2-phpcs-authors <Symfony2-coding-standard@opensky.github.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * CodingStandard_Sniffs_Formatting_BlankLineBeforeReturnSniff.
 *
 * Throws errors if there's no blank line before return statements. Symfony
 * coding standard specifies: "Add a blank line before return statements,
 * unless the return is alone inside a statement-group (like an if statement);"
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Dave Hauenstein <davehauenstein@gmail.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Formatting_BlankLineBeforeReturnSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_RETURN);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens         = $phpcsFile->getTokens();
        $current        = $stackPtr;
        $previousLine   = ($tokens[$stackPtr]['line'] - 1);
        $prevLineTokens = array();

        while ($current >= 0 && $tokens[$current]['line'] >= $previousLine) {
            if ($tokens[$current]['line'] === $previousLine
                && $tokens[$current]['type'] !== 'T_WHITESPACE'
                && $tokens[$current]['type'] !== 'T_COMMENT'
            ) {
                $prevLineTokens[] = $tokens[$current]['type'];
            }

            $current--;
        }

        if (isset($prevLineTokens[0]) === true
            && ($prevLineTokens[0] === 'T_OPEN_CURLY_BRACKET'
            || $prevLineTokens[0] === 'T_COLON')
        ) {
            return;
        } else if (count($prevLineTokens) > 0) {
            $phpcsFile->addError(
                'Missing blank line before return statement',
                $stackPtr
            );
        }

    }//end process()


}//end class

?>

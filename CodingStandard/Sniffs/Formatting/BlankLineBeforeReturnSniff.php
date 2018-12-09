<?php

/**
 * CodingStandard_Sniffs_Formatting_BlankLineBeforeReturnSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * CodingStandard_Sniffs_Formatting_BlankLineBeforeReturnSniff.
 *
 * Throws errors if there's no blank line before return statements. Symfony
 * coding standard specifies: "Add a blank line before return statements,
 * unless the return is alone inside a statement-group (like an if statement);"
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class BlankLineBeforeReturnSniff implements Sniff
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
     * @return integer[]
     */
    public function register()
    {
        return array(T_RETURN);

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
        $tokens    = $phpcsFile->getTokens();
        $prevToken = $phpcsFile->findPrevious(
            Tokens::$emptyTokens,
            ($stackPtr - 1),
            null,
            true
        );

        $expectedBlankLineCount = 1;
        $leadingLinePtr         = $this->getLeadingLinePointer($phpcsFile, $stackPtr, $prevToken);
        $blankLineCount         = ($tokens[$leadingLinePtr]['line'] - ($tokens[$prevToken]['line'] + 1));

        if (isset($tokens[$prevToken]['scope_opener']) === true && $tokens[$prevToken]['scope_opener'] === $prevToken) {
            $expectedBlankLineCount = 0;
        }

        if ($blankLineCount !== $expectedBlankLineCount) {
            $error = 'Expected %s blank line before return statement; %s found';
            $data  = array(
                      $expectedBlankLineCount,
                      $blankLineCount,
                     );
            $phpcsFile->addError($error, $stackPtr, 'BlankLineBeforeReturn', $data);
        }

    }//end process()


    /**
     * Returns leading comment stack pointer or own stack pointer, when no comment found.
     *
     * @param File $phpcsFile    All the tokens found in the document.
     * @param int  $fromStackPtr Start from token.
     * @param int  $toStackPtr   Stop at token.
     *
     * @return int|bool
     */
    protected function getLeadingLinePointer(File $phpcsFile, $fromStackPtr, $toStackPtr)
    {
        $tokens         = $phpcsFile->getTokens();
        $fromToken      = $tokens[$fromStackPtr];
        $prevCommentPtr = $phpcsFile->findPrevious(
            T_COMMENT,
            ($fromStackPtr - 1),
            $toStackPtr
        );

        if ($prevCommentPtr === false) {
            return $fromStackPtr;
        }

        $prevCommentToken = $tokens[$prevCommentPtr];

        if ($prevCommentToken['line'] === ($fromToken['line'] - 1)
            && $prevCommentToken['column'] === $fromToken['column']
        ) {
            return $prevCommentPtr;
        }

        return $fromStackPtr;

    }//end getLeadingLinePointer()


}//end class

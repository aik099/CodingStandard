<?php
/**
 * Ensures doc blocks follow basic formatting.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Commenting\DocCommentSniff as Generic_DocCommentSniff;

/**
 * Ensures doc blocks follow basic formatting.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

class DocCommentSniff extends Generic_DocCommentSniff
{

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
        $tokens       = $phpcsFile->getTokens();
        $commentEnd   = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, ($stackPtr + 1));
        $commentStart = $tokens[$commentEnd]['comment_opener'];

        if ($tokens[$commentStart]['line'] === $tokens[$commentEnd]['line']) {
            $commentText = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

            if (strpos($commentText, '@var') !== false || strpos($commentText, '@type') !== false) {
                // Skip inline block comments with variable type definition.
                return;
            }
        }

        parent::process($phpcsFile, $stackPtr);

    }//end process()


}//end class

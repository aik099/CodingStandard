<?php
/**
 * CodingStandard_Sniffs_Commenting_FunctionCommentSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Standards\Squiz\Sniffs\Commenting\FunctionCommentSniff as Squiz_FunctionCommentSniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Parses and verifies the doc comments for functions.
 *
 * Same as the Squiz standard, but adds support for API tags.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class FunctionCommentSniff extends Squiz_FunctionCommentSniff
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
        parent::process($phpcsFile, $stackPtr);

        $tokens = $phpcsFile->getTokens();
        $find   = Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];

        $empty = array(
                  T_DOC_COMMENT_WHITESPACE,
                  T_DOC_COMMENT_STAR,
                 );

        $short = $phpcsFile->findNext($empty, ($commentStart + 1), $commentEnd, true);
        if ($short === false || $tokens[$short]['code'] !== T_DOC_COMMENT_STRING) {
            return;
        }

        if ($this->isInheritDoc($phpcsFile, $commentStart) === true) {
            return;
        }

        $this->checkShort($phpcsFile, $stackPtr, $tokens[$short]['content'], $short);

    }//end process()

    /**
     * Process the function parameter comments.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack where the comment started.
     *
     * @return void
     */
    protected function processParams(File $phpcsFile, $stackPtr, $commentStart)
    {
        if ($this->isInheritDoc($phpcsFile, $commentStart) === true) {
            return;
        }

        parent::processParams($phpcsFile, $stackPtr, $commentStart);

    }//end processParams()


    /**
     * Process the return comment of this function comment.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack where the comment started.
     *
     * @return void
     */
    protected function processReturn(File $phpcsFile, $stackPtr, $commentStart)
    {
        if ($this->isInheritDoc($phpcsFile, $commentStart) === true) {
            return;
        }

        parent::processReturn($phpcsFile, $stackPtr, $commentStart);

        $return = null;
        $tokens = $phpcsFile->getTokens();

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] === '@return') {
                $return = $tag;
                break;
            }
        }

        if ($return !== null) {
            $returnType = $tokens[($return + 2)]['content'];
            if ($returnType === '$this') {
                $error = 'Function return type "%s" is invalid, please use "static" or "self" instead';
                $data  = array($returnType);
                $phpcsFile->addError($error, $return, 'InvalidThisReturn', $data);
            }
        }

    }//end processReturn()

    /**
     * Process the short description of a function comment.
     *
     * @param File   $phpcsFile The file being scanned.
     * @param int    $stackPtr  The position of the function token
     *                          in the stack passed in $tokens.
     * @param string $short     The content of the short description.
     * @param int    $errorPos  The position where an error should be thrown.
     *
     * @return void
     */
    public function checkShort(File $phpcsFile, $stackPtr, $short, $errorPos)
    {
        $tokens = $phpcsFile->getTokens();

        $classToken = null;
        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            if ($condition === T_CLASS || $condition === T_INTERFACE || $condition === T_TRAIT) {
                $classToken = $condPtr;
                break;
            }
        }

        $isEvent = false;
        if ($classToken !== null) {
            $className = $phpcsFile->getDeclarationName($classToken);
            if (strpos($className, 'EventHandler') !== false) {
                $methodName = $phpcsFile->getDeclarationName($stackPtr);
                if (substr($methodName, 0, 2) === 'On') {
                    $isEvent = true;
                }
            }
        }

        if ($isEvent === true && preg_match('/(\p{Lu}|\[)/u', $short[0]) === 0) {
            $error = 'Event comment short description must start with a capital letter or an [';
            $phpcsFile->addError($error, $errorPos, 'EventShortNotCapital');
        } else if ($isEvent === false && preg_match('/\p{Lu}/u', $short[0]) === 0) {
            $error = 'Doc comment short description must start with a capital letter';
            $phpcsFile->addError($error, $errorPos, 'NonEventShortNotCapital');
        }

    }//end checkShort()


    /**
     * Process any throw tags that this function comment has.
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $stackPtr     The position of the current token
     *                           in the stack passed in $tokens.
     * @param int  $commentStart The position in the stack where the comment started.
     *
     * @return void
     */
    protected function processThrows(File $phpcsFile, $stackPtr, $commentStart)
    {
        parent::processThrows($phpcsFile, $stackPtr, $commentStart);

        $tokens = $phpcsFile->getTokens();

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            // Only process method with scope (e.g. non-abstract methods or methods on the interface).
            if ($tokens[$tag]['content'] !== '@throws' || isset($tokens[$stackPtr]['scope_opener']) === false) {
                continue;
            }

            $throwPtr = $phpcsFile->findNext(
                T_THROW,
                $tokens[$stackPtr]['scope_opener'],
                $tokens[$stackPtr]['scope_closer']
            );

            if ($throwPtr !== false) {
                break;
            }

            $throwsWithString = null;
            $error            = '@throws tag found, but no exceptions are thrown by the function';

            if ($tokens[($tag + 2)]['code'] === T_DOC_COMMENT_STRING) {
                $throwsWithString = true;
            } elseif ($tokens[($tag + 1)]['code'] === T_DOC_COMMENT_WHITESPACE
                && $tokens[($tag + 1)]['content'] === $phpcsFile->eolChar
            ) {
                $throwsWithString = false;
            }

            if ($tokens[($tag - 2)]['code'] === T_DOC_COMMENT_STAR && isset($throwsWithString) === true) {
                $fix = $phpcsFile->addFixableError($error, $tag, 'ExcessiveThrows');

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    $removeEndPtr = $throwsWithString === true ? ($tag + 3) : ($tag + 1);

                    for ($i = ($tag - 4); $i < $removeEndPtr; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            } else {
                $phpcsFile->addError($error, $tag, 'ExcessiveThrows');
            }
        }//end foreach

    }//end processThrows()


    /**
     * Is the comment an inheritdoc?
     *
     * @param File $phpcsFile    The file being scanned.
     * @param int  $commentStart The position in the stack where the comment started.
     *
     * @return bool
     */
    protected function isInheritDoc(File $phpcsFile, $commentStart)
    {
        $tokens = $phpcsFile->getTokens();

        $commentEnd  = $tokens[$commentStart]['comment_closer'];
        $commentText = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

        return stripos($commentText, '{@inheritdoc}') !== false;

    }// end isInheritDoc()

}//end class

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

// @codeCoverageIgnoreStart
if (class_exists('Squiz_Sniffs_Commenting_FunctionCommentSniff', true) === false) {
    $error = 'Class Squiz_Sniffs_Commenting_FunctionCommentSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

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
class CodingStandard_Sniffs_Commenting_FunctionCommentSniff extends Squiz_Sniffs_Commenting_FunctionCommentSniff
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
        parent::process($phpcsFile, $stackPtr);

        $tokens = $phpcsFile->getTokens();
        $find   = PHP_CodeSniffer_Tokens::$methodPrefixes;
        $find[] = T_WHITESPACE;

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);
        if ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG) {
            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];

        $return = null;
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

        $empty = array(
                  T_DOC_COMMENT_WHITESPACE,
                  T_DOC_COMMENT_STAR,
                 );

        $short = $phpcsFile->findNext($empty, ($commentStart + 1), $commentEnd, true);
        if ($short === false || $tokens[$short]['code'] !== T_DOC_COMMENT_STRING) {
            return;
        }

        $this->checkShort($phpcsFile, $stackPtr, $tokens[$short]['content'], $short);

    }//end process()


    /**
     * Process the short description of a function comment.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the function token
     *                                        in the stack passed in $tokens.
     * @param string               $short     The content of the short description.
     * @param int                  $errorPos  The position where an error should be thrown.
     *
     * @return void
     */
    public function checkShort(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $short, $errorPos)
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
     * @param PHP_CodeSniffer_File $phpcsFile    The file being scanned.
     * @param int                  $stackPtr     The position of the current token
     *                                           in the stack passed in $tokens.
     * @param int                  $commentStart The position in the stack where the comment started.
     *
     * @return void
     */
    protected function processThrows(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $commentStart)
    {
        parent::processThrows($phpcsFile, $stackPtr, $commentStart);

        $tokens = $phpcsFile->getTokens();

        foreach ($tokens[$commentStart]['comment_tags'] as $tag) {
            if ($tokens[$tag]['content'] !== '@throws') {
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

}//end class

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

if (class_exists('Squiz_Sniffs_Commenting_FunctionCommentSniff', true) === false) {
    $error = 'Class Squiz_Sniffs_Commenting_FunctionCommentSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}

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

        if (isset($phpcsFile->fixer) === true) {
            $this->processV2($phpcsFile, $stackPtr);
        } else {
            $this->processV1($phpcsFile, $stackPtr);
        }

    }//end process()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * Only works with 2.x versions of PHP_CodeSniffer.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processV2(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
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

    }//end processV2()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * Only works with 1.x versions of PHP_CodeSniffer.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processV1(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->commentParser === null) {
            return;
        }

        $comment = $this->commentParser->getComment();
        if ($comment === null) {
            $this->commentParser = null;
            return;
        }

        $find = array(
                 T_COMMENT,
                 T_DOC_COMMENT,
                 T_CLASS,
                 T_FUNCTION,
                 T_OPEN_TAG,
                );

        $commentEnd   = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        $commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);

        $return = $this->commentParser->getReturn();
        if ($return !== null) {
            $returnType = trim($return->getRawContent());
            $errorPos   = ($commentStart + $return->getLine());

            if ($returnType === '$this') {
                $error = 'Function return type "%s" is invalid, please use "static" or "self" instead';
                $data  = array($returnType);
                $phpcsFile->addError($error, $errorPos, 'InvalidThisReturn', $data);
            }
        }

        $short = trim($comment->getShortComment());
        if ($short === '') {
            $this->commentParser = null;
            return;
        }

        $this->checkShort($phpcsFile, $stackPtr, $short, ($commentStart + 1));
        $this->commentParser = null;

    }//end processV1()


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
            if ($condition === T_CLASS || $condition === T_INTERFACE) {
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


}//end class

?>

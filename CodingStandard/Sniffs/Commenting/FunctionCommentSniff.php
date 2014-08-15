<?php
/**
 * CodingStandard_Sniffs_Commenting_FunctionCommentSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
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

        $tokens = $phpcsFile->getTokens();

        $find = array(
                 T_COMMENT,
                 T_DOC_COMMENT,
                 T_CLASS,
                 T_FUNCTION,
                 T_OPEN_TAG,
                );

        $commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1));
        if ($commentEnd === false) {
            return;
        }

        // If the token that we found was a class or a function, then this
        // function has no doc comment.
        $code = $tokens[$commentEnd]['code'];

        if ($code === T_COMMENT
            || $code !== T_DOC_COMMENT
            || trim($tokens[$commentEnd]['content']) !== '*/'
        ) {
            return;
        }

        // If there is any code between the function keyword and the doc block
        // then the doc block is not for us.
        $ignore    = PHP_CodeSniffer_Tokens::$scopeModifiers;
        $ignore[]  = T_STATIC;
        $ignore[]  = T_WHITESPACE;
        $ignore[]  = T_ABSTRACT;
        $ignore[]  = T_FINAL;
        $prevToken = $phpcsFile->findPrevious($ignore, ($stackPtr - 1), null, true);
        if ($prevToken !== $commentEnd) {
            return;
        }

        $classToken = null;
        foreach ($tokens[$stackPtr]['conditions'] as $condPtr => $condition) {
            if ($condition === T_CLASS || $condition === T_INTERFACE) {
                $classToken = $condPtr;
                break;
            }
        }

        // Find the first doc comment.
        $commentStart  = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
        $commentString = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

        try {
            $this->commentParser = new PHP_CodeSniffer_CommentParser_FunctionCommentParser($commentString, $phpcsFile);
            $this->commentParser->parse();
        } catch (PHP_CodeSniffer_CommentParser_ParserException $e) {
            $line = ($e->getLineWithinComment() + $commentStart);
            $phpcsFile->addError($e->getMessage(), $line, 'FailedParse');
            return;
        }

        $comment = $this->commentParser->getComment();
        if (is_null($comment) === true) {
            return;
        }

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
            return;
        }

        $isEvent = false;
        if ($classToken !== null) {
            $className = $phpcsFile->getDeclarationName($classToken);
            if (substr($className, -12) === 'EventHandler') {
                $methodName = $phpcsFile->getDeclarationName($stackPtr);
                if (substr($methodName, 0, 2) == 'On') {
                    $isEvent = true;
                }
            }
        }

        if ($isEvent === true && preg_match('/(\p{Lu}|\[)/u', $short[0]) === 0) {
            $error = 'Event comment short description must start with a capital letter or an [';
            $phpcsFile->addError($error, ($commentStart + 1), 'EventShortNotCapital');
        } else if ($isEvent === false && preg_match('/\p{Lu}/u', $short[0]) === 0) {
            $error = 'Function comment short description must start with a capital letter';
            $phpcsFile->addError($error, ($commentStart + 1), 'NonEventShortNotCapital');
        }

    }//end process()


}//end class

?>

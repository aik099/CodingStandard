<?php
/**
 * CodingStandard_Sniffs_Commenting_InlineCommentSniff.
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

/**
 * CodingStandard_Sniffs_Commenting_InlineCommentSniff.
 *
 * Checks that there is adequate spacing between comments.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Commenting_InlineCommentSniff implements PHP_CodeSniffer_Sniff
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
        return array(
                T_COMMENT,
                T_DOC_COMMENT_OPEN_TAG,
               );

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
        $tokens = $phpcsFile->getTokens();

        // If this is a function/class/interface doc block comment, skip it.
        // We are only interested in inline doc block comments, which are
        // not allowed.
        if ($tokens[$stackPtr]['code'] === T_DOC_COMMENT_OPEN_TAG) {
            $nextToken = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($stackPtr + 1),
                null,
                true
            );

            $ignore = array(
                       T_CLASS,
                       T_INTERFACE,
                       T_TRAIT,
                       T_FUNCTION,
                       T_CLOSURE,
                       T_PUBLIC,
                       T_PRIVATE,
                       T_PROTECTED,
                       T_FINAL,
                       T_STATIC,
                       T_ABSTRACT,
                       T_CONST,
                       T_PROPERTY,
                      );

            if (in_array($tokens[$nextToken]['code'], $ignore) === true) {
                return;
            }

            if ($phpcsFile->tokenizerType === 'JS') {
                // We allow block comments if a function or object
                // is being assigned to a variable.
                $ignore    = PHP_CodeSniffer_Tokens::$emptyTokens;
                $ignore[]  = T_EQUAL;
                $ignore[]  = T_STRING;
                $ignore[]  = T_OBJECT_OPERATOR;
                $nextToken = $phpcsFile->findNext($ignore, ($nextToken + 1), null, true);
                if ($tokens[$nextToken]['code'] === T_FUNCTION
                    || $tokens[$nextToken]['code'] === T_CLOSURE
                    || $tokens[$nextToken]['code'] === T_OBJECT
                    || $tokens[$nextToken]['code'] === T_PROTOTYPE
                ) {
                    return;
                }
            }

            $prevToken = $phpcsFile->findPrevious(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($stackPtr - 1),
                null,
                true
            );

            if ($tokens[$prevToken]['code'] === T_OPEN_TAG) {
                return;
            }

            // Only error once per comment.
            if ($tokens[$stackPtr]['content'] === '/**') {
                $commentEnd  = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, ($stackPtr + 1));
                $commentText = $phpcsFile->getTokensAsString($stackPtr, (($commentEnd - $stackPtr) + 1));

                if (strpos($commentText, '@var') === false && strpos($commentText, '@type') === false) {
                    $error = 'Inline doc block comments are not allowed; use "/* Comment */" or "// Comment" instead';
                    $phpcsFile->addError($error, $stackPtr, 'DocBlock');
                }
            }
        }//end if

        if ($tokens[$stackPtr]['content']{0} === '#') {
            $error = 'Perl-style comments are not allowed; use "// Comment" instead';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'WrongStyle');
            if ($fix === true) {
                $comment = ltrim($tokens[$stackPtr]['content'], "# \t");
                $phpcsFile->fixer->replaceToken($stackPtr, "// $comment");
            }
        }

        // We don't want end of block comments. If the last comment is a closing
        // curly brace.
        $previousContent = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if ($tokens[$previousContent]['line'] === $tokens[$stackPtr]['line']) {
            if ($tokens[$previousContent]['code'] === T_CLOSE_CURLY_BRACKET) {
                return;
            }

            // Special case for JS files.
            if ($tokens[$previousContent]['code'] === T_COMMA
                || $tokens[$previousContent]['code'] === T_SEMICOLON
            ) {
                $lastContent = $phpcsFile->findPrevious(T_WHITESPACE, ($previousContent - 1), null, true);
                if ($tokens[$lastContent]['code'] === T_CLOSE_CURLY_BRACKET) {
                    return;
                }
            }
        }

        $comment = rtrim($tokens[$stackPtr]['content']);

        // Only want inline comments.
        if (substr($comment, 0, 2) !== '//') {
            return;
        }

        $spaceCount    = 0;
        $commentLength = strlen($comment);
        for ($i = 2; $i < $commentLength; $i++) {
            if ($comment[$i] !== ' ' && $comment[$i] !== "\t") {
                break;
            }

            $spaceCount++;
        }

        $fix = false;
        if ($spaceCount === 0) {
            $error = 'No space before comment text; expected "// %s" but found "%s"';
            $data  = array(
                      ltrim(substr($comment, 2)),
                      $comment,
                     );
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceBefore', $data);
        } else if ($spaceCount > 1) {
            $error = '%s spaces found before inline comment line; use block comment if you need indentation';
            $data  = array(
                      $spaceCount,
                      substr($comment, (2 + $spaceCount)),
                      $comment,
                     );
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpacingBefore', $data);
        }//end if

        if ($fix === true) {
            $phpcsFile->fixer->beginChangeset();
            $newComment = '// '.ltrim($tokens[$stackPtr]['content'], "/\t ");
            $phpcsFile->fixer->replaceToken($stackPtr, $newComment);
            $phpcsFile->fixer->endChangeset();
        }

        // The below section determines if a comment block is correctly capitalised,
        // and ends in a full-stop. It will find the last comment in a block, and
        // work its way up.
        $nextComment = $phpcsFile->findNext(array(T_COMMENT), ($stackPtr + 1), null, false);

        if (($nextComment !== false) && (($tokens[$nextComment]['line']) === ($tokens[$stackPtr]['line'] + 1))) {
            return;
        }

        $lastComment = $stackPtr;
        while (($topComment = $phpcsFile->findPrevious(array(T_COMMENT), ($lastComment - 1), null, false)) !== false) {
            if ($tokens[$topComment]['line'] !== ($tokens[$lastComment]['line'] - 1)) {
                break;
            }

            $lastComment = $topComment;
        }

        $topComment  = $lastComment;
        $commentText = '';

        for ($i = $topComment; $i <= $stackPtr; $i++) {
            if ($tokens[$i]['code'] === T_COMMENT) {
                $commentText .= trim(substr($tokens[$i]['content'], 2));
            }
        }

        if ($commentText === '') {
            $error = 'Blank comments are not allowed';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'Empty');
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($stackPtr, '');
            }

            return;
        }

        if (preg_match('/(\p{Lu}|\d)/u', $commentText[0]) === 0) {
            $error = 'Inline comments must start with a capital letter or digit';
            $phpcsFile->addError($error, $topComment, 'NotCapital');
        }

        $commentCloser   = $commentText[(strlen($commentText) - 1)];
        $acceptedClosers = array(
                            'full-stops'        => '.',
                            'exclamation marks' => '!',
                            'question marks'    => '?',
                            'commas'            => ',',
                            'or semicolons'     => ';',
                           );

        if (in_array($commentCloser, $acceptedClosers) === false) {
            $error = 'Inline comments must end in %s';
            $ender = '';
            foreach ($acceptedClosers as $closerName => $symbol) {
                $ender .= ' '.$closerName.',';
            }

            $ender = trim($ender, ' ,');
            $data  = array($ender);
            $phpcsFile->addError($error, $stackPtr, 'InvalidEndChar', $data);
        }

        // Finally, the line below the last comment cannot be empty if this inline
        // comment is on a line by itself.
        if ($tokens[$previousContent]['line'] < $tokens[$stackPtr]['line']) {
            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                if ($tokens[$i]['line'] === ($tokens[$stackPtr]['line'] + 1)) {
                    if ($tokens[$i]['code'] !== T_WHITESPACE) {
                        return;
                    }
                } else if ($tokens[$i]['line'] > ($tokens[$stackPtr]['line'] + 1)) {
                    break;
                }
            }

            $error = 'There must be no blank line following an inline comment';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpacingAfter');
            if ($fix === true) {
                $next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
                $phpcsFile->fixer->beginChangeset();
                for ($i = ($stackPtr + 1); $i < $next; $i++) {
                    if ($tokens[$i]['line'] === $tokens[$next]['line']) {
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end process()


}//end class

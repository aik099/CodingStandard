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

namespace CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

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
class InlineCommentSniff implements Sniff
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // If this is a function/class/interface doc block comment, skip it.
        // We are only interested in inline doc block comments, which are
        // not allowed.
        if ($tokens[$stackPtr]['code'] === T_DOC_COMMENT_OPEN_TAG) {
            $nextToken = $phpcsFile->findNext(
                Tokens::$emptyTokens,
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
                $ignore    = Tokens::$emptyTokens;
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
                Tokens::$emptyTokens,
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

        if ($tokens[$stackPtr]['content'][0] === '#') {
            $error = 'Perl-style comments are not allowed; use "// Comment" instead';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'WrongStyle');
            if ($fix === true) {
                $comment = ltrim($tokens[$stackPtr]['content'], "# \t");
                $phpcsFile->fixer->replaceToken($stackPtr, "// $comment");
            }
        }

        // We don't want:
        // - end of block comments, if the last comment is a closing curly brace
        // - closing comment of previous control structure (see "WhiteSpace.ControlStructureSpacing")
        $previousContent = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if ($tokens[$previousContent]['line'] === $tokens[$stackPtr]['line']
            || $tokens[$previousContent]['line'] === ($tokens[$stackPtr]['line'] - 1)
        ) {
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

        if (trim(substr($comment, 2)) !== '') {
            $spaceCount = 0;
            $tabFound   = false;

            $commentLength = strlen($comment);
            for ($i = 2; $i < $commentLength; $i++) {
                if ($comment[$i] === "\t") {
                    $tabFound = true;
                    break;
                }

                if ($comment[$i] !== ' ') {
                    break;
                }

                $spaceCount++;
            }

            $fix = false;
            if ($tabFound === true) {
                $error = 'Tab found before comment text; expected "// %s" but found "%s"';
                $data  = array(
                          ltrim(substr($comment, 2)),
                          $comment,
                         );
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'TabBefore', $data);
            } elseif ($spaceCount === 0) {
                $error = 'No space found before comment text; expected "// %s" but found "%s"';
                $data  = array(
                          substr($comment, 2),
                          $comment,
                         );
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoSpaceBefore', $data);
            } elseif ($spaceCount > 1) {
                $error = 'Expected 1 space before comment text but found %s; use block comment if you need indentation';
                $data  = array(
                          $spaceCount,
                          substr($comment, (2 + $spaceCount)),
                          $comment,
                         );
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpacingBefore', $data);
            }//end if

            if ($fix === true) {
                $newComment = '// '.ltrim($tokens[$stackPtr]['content'], "/\t ");
                $phpcsFile->fixer->replaceToken($stackPtr, $newComment);
            }
        }//end if

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

        // If the first character is now uppercase and is not
        // a non-letter character, throw an error.
        // \p{Lu} : an uppercase letter that has a lowercase variant.
        // \P{L}  : a non-letter character.
        if (preg_match('/\p{Lu}|\P{L}/u', $commentText[0]) === 0) {
            $error = 'Inline comments must start with a capital letter';
            $phpcsFile->addError($error, $topComment, 'NotCapital');
        }

        // Only check the end of comment character if the start of the comment
        // is a letter, indicating that the comment is just standard text.
        if (preg_match('/\P{L}/u', $commentText[0]) === 0) {
            $commentCloser   = $commentText[(strlen($commentText) - 1)];
            $acceptedClosers = array(
                                'full-stops'        => '.',
                                'exclamation marks' => '!',
                                'or question marks' => '?',
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
        }

        // Finally, the line below the last comment cannot be empty if this inline
        // comment is on a line by itself.
        if ($tokens[$previousContent]['line'] < $tokens[$stackPtr]['line']) {
            for ($i = ($stackPtr + 1); $i < $phpcsFile->numTokens; $i++) {
                if ($tokens[$i]['line'] === ($tokens[$stackPtr]['line'] + 1)) {
                    if ($tokens[$i]['code'] !== T_WHITESPACE) {
                        return;
                    }
                } elseif ($tokens[$i]['line'] > ($tokens[$stackPtr]['line'] + 1)) {
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

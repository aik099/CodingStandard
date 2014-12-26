<?php
/**
 * CodingStandard_Sniffs_WhiteSpace_ControlStructureSpacingSniff.
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
 * CodingStandard_Sniffs_WhiteSpace_ControlStructureSpacingSniff.
 *
 * Checks that control structures have the correct spacing around brackets.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_WhiteSpace_ControlStructureSpacingSniff implements PHP_CodeSniffer_Sniff
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
     * How many spaces should follow the opening bracket.
     *
     * @var int
     */
    public $requiredSpacesAfterOpen = 1;

    /**
     * How many spaces should precede the closing bracket.
     *
     * @var int
     */
    public $requiredSpacesBeforeClose = 1;


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(
                T_IF,
                T_WHILE,
                T_FOREACH,
                T_FOR,
                T_SWITCH,
                T_DO,
                T_ELSE,
                T_ELSEIF,
                T_TRY,
                T_CATCH,
               );

    }//end register()


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
        $this->requiredSpacesAfterOpen   = (int) $this->requiredSpacesAfterOpen;
        $this->requiredSpacesBeforeClose = (int) $this->requiredSpacesBeforeClose;
        $tokens = $phpcsFile->getTokens();

        $this->checkBracketSpacing($phpcsFile, $stackPtr);

        if (isset($tokens[$stackPtr]['scope_closer']) === false) {
            return;
        }

        $this->checkContentInside($phpcsFile, $stackPtr);
        $this->checkLeadingContent($phpcsFile, $stackPtr);
        $this->checkTrailingContent($phpcsFile, $stackPtr);

    }//end process()


    /**
     * Checks bracket spacing.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkBracketSpacing(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['parenthesis_opener']) === false) {
            return;
        }

        $parenOpener    = $tokens[$stackPtr]['parenthesis_opener'];
        $parenCloser    = $tokens[$stackPtr]['parenthesis_closer'];
        $spaceAfterOpen = 0;
        if ($tokens[($parenOpener + 1)]['code'] === T_WHITESPACE) {
            $spaceAfterOpen = $tokens[($parenOpener + 1)]['length'];
        }

        if ($spaceAfterOpen !== $this->requiredSpacesAfterOpen) {
            $error = 'Expected %s spaces after "%s" opening bracket; %s found';
            $data  = array(
                      $this->requiredSpacesAfterOpen,
                      $tokens[$stackPtr]['content'],
                      $spaceAfterOpen,
                     );
            $fix   = $phpcsFile->addFixableError($error, ($parenOpener + 1), 'SpacingAfterOpenBrace', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                for ($i = $spaceAfterOpen; $i < $this->requiredSpacesAfterOpen; $i++) {
                    $phpcsFile->fixer->addContent($parenOpener, ' ');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }

        if ($tokens[$parenOpener]['line'] === $tokens[$parenCloser]['line']) {
            $spaceBeforeClose = 0;
            if ($tokens[($parenCloser - 1)]['code'] === T_WHITESPACE) {
                $spaceBeforeClose = $tokens[($parenCloser - 1)]['length'];
            }

            if ($spaceBeforeClose !== $this->requiredSpacesBeforeClose) {
                $error = 'Expected %s spaces before "%s" closing bracket; %s found';
                $data  = array(
                          $this->requiredSpacesBeforeClose,
                          $tokens[$stackPtr]['content'],
                          $spaceBeforeClose,
                         );
                $fix   = $phpcsFile->addFixableError($error, ($parenCloser - 1), 'SpaceBeforeCloseBrace', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = $spaceBeforeClose; $i < $this->requiredSpacesBeforeClose; $i++) {
                        $phpcsFile->fixer->addContentBefore($parenCloser, ' ');
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            }
        }//end if

    }//end checkBracketSpacing()


    /**
     * Checks content inside.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkContentInside(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $scopeOpener = $tokens[$stackPtr]['scope_opener'];
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        $firstContent = $phpcsFile->findNext(
            T_WHITESPACE,
            ($scopeOpener + 1),
            null,
            true
        );

        if ($tokens[$firstContent]['line'] !== ($tokens[$scopeOpener]['line'] + 1)) {
            $error = 'Expected 0 blank lines at start of "%s" control structure; %s found';
            $data  = array(
                      $tokens[$stackPtr]['content'],
                      ($tokens[$firstContent]['line'] - ($tokens[$scopeOpener]['line'] + 1)),
                     );
            $fix   = $phpcsFile->addFixableError($error, $scopeOpener, 'SpacingBeforeOpen', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                for ($i = ($scopeOpener + 1); $i < $firstContent; $i++) {
                    if ($tokens[$i]['line'] === $tokens[$firstContent]['line']) {
                        // Keep existing indentation.
                        break;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->addNewline($scopeOpener);
                $phpcsFile->fixer->endChangeset();
            }
        }//end if

        if ($firstContent !== $scopeCloser) {
            // Not an empty control structure.
            $lastContent = $phpcsFile->findPrevious(
                T_WHITESPACE,
                ($scopeCloser - 1),
                null,
                true
            );

            if ($tokens[$lastContent]['line'] !== ($tokens[$scopeCloser]['line'] - 1)) {
                $error = 'Expected 0 blank lines at end of "%s" control structure; %s found';
                $data  = array(
                          $tokens[$stackPtr]['content'],
                          (($tokens[$scopeCloser]['line'] - 1) - $tokens[$lastContent]['line']),
                         );
                $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'SpacingAfterClose', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($lastContent + 1); $i < $scopeCloser; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$scopeCloser]['line']) {
                            // Keep existing indentation.
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewline($lastContent);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        }//end if

    }//end checkContentInside()


    /**
     * Checks leading content.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkLeadingContent(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $leadingContent = $phpcsFile->findPrevious(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($stackPtr - 1),
            null,
            true
        );

        if ($tokens[$leadingContent]['code'] === T_OPEN_TAG) {
            // At the beginning of the script or embedded code.
            return;
        }

        $leadingLineNumber = $this->getLeadingLineNumber($phpcsFile, $stackPtr, $leadingContent);

        if ($tokens[$leadingContent]['code'] === T_OPEN_CURLY_BRACKET
            || $this->insideSwitchCase($phpcsFile, $leadingContent) === true
            || ($this->elseOrElseIf($phpcsFile, $stackPtr) === true && $this->ifOrElseIf($phpcsFile, $leadingContent) === true)
            || ($this->isCatch($phpcsFile, $stackPtr) === true && $this->isTry($phpcsFile, $leadingContent) === true)
        ) {
            if ($this->isFunction($phpcsFile, $leadingContent) === true) {
                // The previous content is the opening brace of a function
                // so normal function rules apply and we can ignore it.
                return;
            }

            if ($this->isClosure($phpcsFile, $stackPtr, $leadingContent) === true) {
                return;
            }

            if ($tokens[$leadingContent]['line'] !== ($leadingLineNumber - 1)) {
                $diff = ($leadingLineNumber - 1) - $tokens[$leadingContent]['line'];
                if ($diff < 0) {
                    $diff = 0;
                }

                $data  = array(
                          $tokens[$stackPtr]['content'],
                          $diff,
                         );
                $error = 'Expected 0 blank lines before "%s" control structure; %s found';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'LineBeforeOpen', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($leadingContent + 1); $i < $stackPtr; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$stackPtr]['line']) {
                            // Keep existing indentation.
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewline($leadingContent);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        } else if ($tokens[$leadingContent]['line'] === ($leadingLineNumber - 1)) {
            // Code on the previous line before control structure start.
            $data  = array($tokens[$stackPtr]['content']);
            $error = 'No blank line found before "%s" control structure';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoLineBeforeOpen', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($leadingContent);
                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end checkLeadingContent()


    /**
     * Returns leading comment line number or own line number, when no comment found.
     *
     * @param PHP_CodeSniffer_File $phpcsFile    All the tokens found in the document.
     * @param int                  $fromStackPtr Start from token.
     * @param int                  $toStackPtr   Stop at token.
     *
     * @return int|bool
     */
    protected function getLeadingLineNumber(PHP_CodeSniffer_File $phpcsFile, $fromStackPtr, $toStackPtr)
    {
        $tokens         = $phpcsFile->getTokens();
        $fromToken      = $tokens[$fromStackPtr];
        $prevCommentPtr = $phpcsFile->findPrevious(
            T_COMMENT,
            ($fromStackPtr - 1),
            $toStackPtr
        );

        if ($prevCommentPtr === false) {
            return $fromToken['line'];
        }

        $prevCommentToken = $tokens[$prevCommentPtr];

        if ($prevCommentToken['line'] === ($fromToken['line'] - 1)
            && $prevCommentToken['column'] === $fromToken['column']
        ) {
            return $prevCommentToken['line'];
        }

        return $fromToken['line'];

    }//end getLeadingLineNumber()


    /**
     * Checks trailing content.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkTrailingContent(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens          = $phpcsFile->getTokens();
        $scopeCloser     = $this->getScopeCloser($phpcsFile, $stackPtr);
        $trailingContent = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($scopeCloser + 1),
            null,
            true
        );

        if ($tokens[$trailingContent]['code'] === T_CLOSE_TAG) {
            // At the end of the script or embedded code.
            return;
        }

        $trailingLineNumber = $this->getTrailingLineNumber(
            $phpcsFile,
            $tokens[$stackPtr]['scope_closer'],
            $trailingContent
        );

        if ($tokens[$trailingContent]['code'] === T_CLOSE_CURLY_BRACKET
            || $this->insideSwitchCase($phpcsFile, $trailingContent) === true
            || ($this->isTry($phpcsFile, $stackPtr) === true && $this->isCatch($phpcsFile, $trailingContent) === true)
        ) {
            if ($this->isFunction($phpcsFile, $trailingContent) === true) {
                // The next content is the closing brace of a function
                // so normal function rules apply and we can ignore it.
                return;
            }

            if ($this->isClosure($phpcsFile, $stackPtr, $trailingContent) === true) {
                return;
            }

            if ($tokens[$trailingContent]['line'] !== ($trailingLineNumber + 1)) {
                $diff = $tokens[$trailingContent]['line'] - ($trailingLineNumber + 1);
                if ($diff < 0) {
                    $diff = 0;
                }

                $data  = array(
                          $tokens[$stackPtr]['content'],
                          $diff,
                         );
                $error = 'Expected 0 blank lines after "%s" control structure; %s found';
                $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'LineAfterClose', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($scopeCloser + 1); $i < $trailingContent; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$trailingContent]['line']) {
                            // Keep existing indentation.
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewlineBefore($this->findFirstOnLine($phpcsFile, $trailingContent));
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        } else if ($tokens[$trailingContent]['line'] === ($trailingLineNumber + 1)) {
            // Code on the next line after control structure scope closer.
            if ($this->elseOrElseIf($phpcsFile, $trailingContent) === true) {
                return;
            }

            $error = 'No blank line found after "%s" control structure';
            $data  = array($tokens[$stackPtr]['content']);
            $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'NoLineAfterClose', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewlineBefore($this->findFirstOnLine($phpcsFile, $trailingContent));
                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end checkTrailingContent()


    /**
     * Returns scope closer  with special check for "do...while" statements.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return int|bool
     */
    protected function getScopeCloser(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        if ($tokens[$stackPtr]['code'] !== T_DO) {
            return $scopeCloser;
        }

        $trailingContent = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($scopeCloser + 1),
            null,
            true
        );

        if ($tokens[$trailingContent]['code'] === T_WHILE) {
            return ($tokens[$trailingContent]['parenthesis_closer'] + 1);
        }

        // @codeCoverageIgnoreStart
        $phpcsFile->addError('Expected "while" not found after "do"', $stackPtr, 'InvalidDo');

        return $scopeCloser;
        // @codeCoverageIgnoreEnd

    }//end getScopeCloser()


    /**
     * Returns trailing comment line number or own line number, when no comment found.
     *
     * @param PHP_CodeSniffer_File $phpcsFile    All the tokens found in the document.
     * @param int                  $fromStackPtr Start from token.
     * @param int                  $toStackPtr   Stop at token.
     *
     * @return int|bool
     */
    protected function getTrailingLineNumber(PHP_CodeSniffer_File $phpcsFile, $fromStackPtr, $toStackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $fromToken   = $tokens[$fromStackPtr];
        $nextComment = $phpcsFile->findNext(
            T_COMMENT,
            ($fromStackPtr + 1),
            $toStackPtr
        );

        if ($nextComment === false) {
            return $fromToken['line'];
        }

        $nextCommentToken = $tokens[$nextComment];

        if ($nextCommentToken['line'] === ($fromToken['line'] + 1)
            && $nextCommentToken['column'] === $fromToken['column']
        ) {
            return $nextCommentToken['line'];
        }

        return $fromToken['line'];

    }//end getTrailingLineNumber()


    /**
     * Finds first token on a line.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $start     Start from token.
     *
     * @return int | bool
     */
    public function findFirstOnLine(PHP_CodeSniffer_File $phpcsFile, $start)
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $start; $i >= 0; $i--) {
            if ($tokens[$i]['line'] === $tokens[$start]['line']) {
                continue;
            }

            return ($i + 1);
        }

        return false;

    }//end findFirstOnLine()


    /**
     * Detects, that we're at the edge (beginning or ending) of CASE/DEFAULT with SWITCH statement.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function insideSwitchCase(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_CASE, T_DEFAULT));

    }//end insideSwitchCase()


    /**
     * Detects, that it is a closing brace of IF/ELSEIF.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function ifOrElseIf(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_IF, T_ELSEIF));

    }//end ifOrElseIf()


    /**
     * Detects, that it is a closing brace of ELSE/ELSEIF.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function elseOrElseIf(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_ELSE, T_ELSEIF));

    }//end elseOrElseIf()


    /**
     * Detects, that it is a closing brace of TRY.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isTry(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_TRY);

    }//end isTry()


    /**
     * Detects, that it is a closing brace of CATCH.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isCatch(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_CATCH);

    }//end isTry()


    /**
     * Determines that a function is located at given position.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isFunction(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_FUNCTION);

    }//end isFunction()


    /**
     * Determines that a closure is located at given position.
     *
     * @param PHP_CodeSniffer_File $phpcsFile         The file being scanned.
     * @param int                  $stackPtr          The position of the current token.
     *                                                in the stack passed in $tokens.
     * @param int                  $scopeConditionPtr Position of scope condition.
     *
     * @return bool
     */
    protected function isClosure(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $scopeConditionPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $owner  = $tokens[$scopeConditionPtr]['scope_condition'];

        if ($tokens[$owner]['code'] === T_CLOSURE
            && ($phpcsFile->hasCondition($stackPtr, T_FUNCTION) === true
            || $phpcsFile->hasCondition($stackPtr, T_CLOSURE) === true
            || isset($tokens[$stackPtr]['nested_parenthesis']) === true)
        ) {
            return true;
        }

        return false;

    }//end isClosure()


    /**
     * Detects, that it is a closing brace of ELSE/ELSEIF.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param int|array            $types     The type(s) of tokens to search for.
     *
     * @return bool
     */
    protected function isScopeCondition(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $types)
    {
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition']) === true) {
            $owner = $tokens[$stackPtr]['scope_condition'];

            if (in_array($tokens[$owner]['code'], (array)$types) === true) {
                return true;
            }
        }

        return false;

    }//end isScopeCondition()


}//end class

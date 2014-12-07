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
     * @return array
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
            $spaceAfterOpen = strlen($tokens[($parenOpener + 1)]['content']);
        }

        if ($spaceAfterOpen !== $this->requiredSpacesAfterOpen) {
            $error = 'Expected %s spaces after "%s" opening bracket; %s found';
            $data  = array(
                      $this->requiredSpacesAfterOpen,
                      $tokens[$stackPtr]['content'],
                      $spaceAfterOpen,
                     );
            $phpcsFile->addError($error, ($parenOpener + 1), 'SpacingAfterOpenBrace', $data);
        }

        if ($tokens[$parenOpener]['line'] === $tokens[$parenCloser]['line']) {
            $spaceBeforeClose = 0;
            if ($tokens[($parenCloser - 1)]['code'] === T_WHITESPACE) {
                $spaceBeforeClose = strlen($tokens[($parenCloser - 1)]['content']);
            }

            if ($spaceBeforeClose !== $this->requiredSpacesBeforeClose) {
                $error = 'Expected %s spaces before "%s" closing bracket; %s found';
                $data  = array(
                          $this->requiredSpacesBeforeClose,
                          $tokens[$stackPtr]['content'],
                          $spaceBeforeClose,
                         );
                $phpcsFile->addError($error, ($parenCloser - 1), 'SpaceBeforeCloseBrace', $data);
            }
        }

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
            $phpcsFile->addError($error, $scopeOpener, 'SpacingBeforeOpen', $data);
        }

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
                $phpcsFile->addError($error, $scopeCloser, 'SpacingAfterClose', $data);
            }
        }

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
                $data  = array(
                          $tokens[$stackPtr]['content'],
                          (($leadingLineNumber - 1) - $tokens[$leadingContent]['line']),
                         );
                $error = 'Expected 0 blank lines before "%s" control structure; %s found';
                $phpcsFile->addError($error, $stackPtr, 'LineBeforeOpen', $data);
            }
        } else if ($tokens[$leadingContent]['line'] === ($leadingLineNumber - 1)) {
            // Code on the previous line before control structure start.
            $data  = array($tokens[$stackPtr]['content']);
            $error = 'No blank line found before "%s" control structure';
            $phpcsFile->addError($error, $stackPtr, 'NoLineBeforeOpen', $data);
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
        $scopeCloser     = $tokens[$stackPtr]['scope_closer'];
        $trailingContent = $this->searchTrailingContent($phpcsFile, $stackPtr);

        if ($tokens[$trailingContent]['code'] === T_CLOSE_TAG) {
            // At the end of the script or embedded code.
            return;
        }

        $trailingLineNumber = $this->getTrailingLineNumber($phpcsFile, $scopeCloser, $trailingContent);

        if ($tokens[$trailingContent]['code'] === T_CLOSE_CURLY_BRACKET
            || $this->insideSwitchCase($phpcsFile, $trailingContent) === true
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
                $error = 'Expected 0 blank lines after "%s" control structure; %s found';
                $data  = array(
                          $tokens[$stackPtr]['content'],
                          ($tokens[$trailingContent]['line'] - ($trailingLineNumber + 1)),
                         );
                $phpcsFile->addError($error, $scopeCloser, 'LineAfterClose', $data);
            }
        } else if ($tokens[$trailingContent]['line'] === ($trailingLineNumber + 1)) {
            // Code on the next line after control structure scope closer.
            if ($this->elseOrElseIf($phpcsFile, $trailingContent) === true) {
                return;
            }

            $error = 'No blank line found after "%s" control structure';
            $data  = array($tokens[$stackPtr]['content']);
            $phpcsFile->addError($error, $scopeCloser, 'NoLineAfterClose', $data);
        }//end if

    }//end checkTrailingContent()


    /**
     * Searches for trailing content with special check for "do...while" statements.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return int|bool
     */
    protected function searchTrailingContent(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        $trailingContent = $phpcsFile->findNext(
            PHP_CodeSniffer_Tokens::$emptyTokens,
            ($scopeCloser + 1),
            null,
            true
        );

        if ($tokens[$stackPtr]['code'] === T_DO && $tokens[$trailingContent]['code'] === T_WHILE) {
            $conditionCloser = $tokens[$trailingContent]['parenthesis_closer'];

            // Look right after the semicolon placed after closing brace of condition.
            $trailingContent = $phpcsFile->findNext(
                PHP_CodeSniffer_Tokens::$emptyTokens,
                ($conditionCloser + 2),
                null,
                true
            );
        }

        return $trailingContent;

    }//end searchTrailingContent()


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
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition']) === true) {
            $owner = $tokens[$stackPtr]['scope_condition'];

            if ($tokens[$owner]['code'] === T_CASE || $tokens[$owner]['code'] === T_DEFAULT) {
                return true;
            }
        }

        return false;

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
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition']) === true) {
            $owner = $tokens[$stackPtr]['scope_condition'];

            if ($tokens[$owner]['code'] === T_IF || $tokens[$owner]['code'] === T_ELSEIF) {
                return true;
            }
        }

        return false;

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
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition']) === true) {
            $owner = $tokens[$stackPtr]['scope_condition'];

            if ($tokens[$owner]['code'] === T_ELSE || $tokens[$owner]['code'] === T_ELSEIF) {
                return true;
            }
        }

        return false;

    }//end elseOrElseIf()


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
        $tokens = $phpcsFile->getTokens();

        if (isset($tokens[$stackPtr]['scope_condition']) === true) {
            $owner = $tokens[$stackPtr]['scope_condition'];

            if ($tokens[$owner]['code'] === T_FUNCTION) {
                return true;
            }
        }

        return false;

    }//end isFunction()


    /**
     * Determines that a closure is located at given position.
     *
     * @param PHP_CodeSniffer_File $phpcsFile         The file being scanned.
     * @param int                  $stackPtr          The position of the current token
     *                                                in the stack passed in $tokens.
     *
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


}//end class

?>

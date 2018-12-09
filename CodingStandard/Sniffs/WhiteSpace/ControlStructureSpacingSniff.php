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

namespace CodingStandard\Sniffs\WhiteSpace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

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
class ControlStructureSpacingSniff implements Sniff
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkBracketSpacing(File $phpcsFile, $stackPtr)
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkContentInside(File $phpcsFile, $stackPtr)
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
            $data = array($tokens[$stackPtr]['content']);
            $diff = $tokens[$firstContent]['line'] - ($tokens[$scopeOpener]['line'] + 1);
            if ($diff < 0) {
                $error = 'Opening brace of the "%s" control structure must be last content on the line';
                $fix   = $phpcsFile->addFixableError($error, $scopeOpener, 'ContentAfterOpen', $data);
            } else {
                $data[] = $diff;
                $error  = 'Expected 0 blank lines at start of "%s" control structure; %s found';
                $fix    = $phpcsFile->addFixableError($error, $scopeOpener, 'SpacingBeforeOpen', $data);
            }

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                for ($i = ($firstContent - 1); $i > $scopeOpener; $i--) {
                    if ($tokens[$i]['line'] === $tokens[$firstContent]['line']
                        || $tokens[$i]['line'] === $tokens[$scopeOpener]['line']
                    ) {
                        // Keep existing indentation.
                        continue;
                    }

                    $phpcsFile->fixer->replaceToken($i, '');
                }

                if ($diff < 0) {
                    $phpcsFile->fixer->addNewline($scopeOpener);
                }

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
                $data = array($tokens[$stackPtr]['content']);
                $diff = (($tokens[$scopeCloser]['line'] - 1) - $tokens[$lastContent]['line']);

                if ($diff < 0) {
                    $error = 'Closing brace of the "%s" control structure must be first content on the line';
                    $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'SpacingAfterClose', $data);
                } else {
                    $data[] = $diff;
                    $error  = 'Expected 0 blank lines at end of "%s" control structure; %s found';
                    $fix    = $phpcsFile->addFixableError($error, $scopeCloser, 'SpacingAfterClose', $data);
                }

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($lastContent + 1); $i < $scopeCloser; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$scopeCloser]['line']
                            || $tokens[$i]['line'] === $tokens[$lastContent]['line']
                        ) {
                            // Keep existing indentation.
                            continue;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    if ($diff < 0) {
                        $phpcsFile->fixer->addNewline($lastContent);
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        }//end if

    }//end checkContentInside()


    /**
     * Checks leading content.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkLeadingContent(File $phpcsFile, $stackPtr)
    {
        $tokens                   = $phpcsFile->getTokens();
        $leadingContent           = $this->getLeadingContent($phpcsFile, $stackPtr);
        $controlStructureStartPtr = $this->getLeadingCommentOrSelf($phpcsFile, $stackPtr);

        if ($tokens[$leadingContent]['code'] === T_OPEN_TAG) {
            // At the beginning of the script or embedded code.
            return;
        }

        $firstNonWhitespace = $phpcsFile->findPrevious(
            T_WHITESPACE,
            ($controlStructureStartPtr - 1),
            $leadingContent,
            true
        );
        $firstNonWhitespace = $firstNonWhitespace ?: $leadingContent;
        $leadingLineNumber  = $tokens[$firstNonWhitespace]['line'];

        if ($tokens[$leadingContent]['code'] === T_OPEN_CURLY_BRACKET
            || $this->insideSwitchCase($phpcsFile, $leadingContent) === true
            || ($this->elseOrElseIf($phpcsFile, $stackPtr) === true && $this->ifOrElseIf($phpcsFile, $leadingContent) === true)
            || ($this->isCatch($phpcsFile, $stackPtr) === true && $this->isTryOrCatch($phpcsFile, $leadingContent) === true)
        ) {
            if ($this->isFunction($phpcsFile, $leadingContent) === true) {
                // The previous content is the opening brace of a function
                // so normal function rules apply and we can ignore it.
                return;
            }

            if ($this->isClosure($phpcsFile, $stackPtr, $leadingContent) === true) {
                return;
            }

            if ($tokens[$controlStructureStartPtr]['line'] !== ($leadingLineNumber + 1)) {
                $data = array($tokens[$stackPtr]['content']);
                $diff = $tokens[$controlStructureStartPtr]['line'] - ($leadingLineNumber + 1);
                if ($diff < 0) {
                    $error = 'Beginning of the "%s" control structure must be first content on the line';
                    $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'ContentBeforeStart', $data);
                } else {
                    $data[] = $diff;
                    $error  = 'Expected 0 blank lines before "%s" control structure; %s found';
                    $fix    = $phpcsFile->addFixableError($error, $stackPtr, 'LineBeforeOpen', $data);
                }

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($firstNonWhitespace + 1); $i < $controlStructureStartPtr; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$controlStructureStartPtr]['line']) {
                            // Keep existing indentation.
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewline($firstNonWhitespace);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        } else if ($tokens[$controlStructureStartPtr]['line'] === ($leadingLineNumber + 1)) {
            // Code on the previous line before control structure start.
            $data  = array($tokens[$stackPtr]['content']);
            $error = 'No blank line found before "%s" control structure';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'NoLineBeforeOpen', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($firstNonWhitespace);
                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end checkLeadingContent()


    /**
     * Returns leading non-whitespace/comment token.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return int|bool
     */
    protected function getLeadingContent(File $phpcsFile, $stackPtr)
    {
        $prevNonWhitespace = $phpcsFile->findPrevious(
            array(
             T_WHITESPACE,
             T_COMMENT,
            ),
            ($stackPtr - 1),
            null,
            true
        );

        return $prevNonWhitespace;

    }//end getLeadingContent()

    /**
     * Returns leading comment or self.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool|int
     */
    protected function getLeadingCommentOrSelf(File $phpcsFile, $stackPtr)
    {
        $prevTokens = array($stackPtr);
        $tokens     = $phpcsFile->getTokens();

        do {
            $prev    = end($prevTokens);
            $newPrev = $phpcsFile->findPrevious(
                T_WHITESPACE,
                ($prev - 1),
                null,
                true
            );

            if ($tokens[$newPrev]['code'] === T_COMMENT
                && $tokens[$newPrev]['line'] === ($tokens[$prev]['line'] - 1)
            ) {
                $prevTokens[] = $newPrev;
            } else {
                break;
            }
        } while (true);

        return end($prevTokens);

    }//end getLeadingCommentOrSelf()

    /**
     * Checks trailing content.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    protected function checkTrailingContent(File $phpcsFile, $stackPtr)
    {
        $tokens                 = $phpcsFile->getTokens();
        $scopeCloser            = $this->getScopeCloser($phpcsFile, $stackPtr);
        $trailingContent        = $this->getTrailingContent($phpcsFile, $scopeCloser);
        $controlStructureEndPtr = $this->getTrailingCommentOrSelf($phpcsFile, $scopeCloser);

        if ($tokens[$trailingContent]['code'] === T_CLOSE_TAG) {
            // At the end of the script or embedded code.
            return;
        }

        $lastNonWhitespace = $phpcsFile->findNext(
            T_WHITESPACE,
            ($controlStructureEndPtr + 1),
            $trailingContent,
            true
        );
        $lastNonWhitespace  = $lastNonWhitespace ?: $trailingContent;
        $trailingLineNumber = $tokens[$lastNonWhitespace]['line'];

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

            if ($tokens[$controlStructureEndPtr]['line'] !== ($trailingLineNumber - 1)) {
                $diff  = ($trailingLineNumber - 1) - $tokens[$controlStructureEndPtr]['line'];
                $data  = array(
                          $tokens[$stackPtr]['content'],
                          $diff,
                         );
                $error = 'Expected 0 blank lines after "%s" control structure; %s found';
                $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'LineAfterClose', $data);

                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($controlStructureEndPtr + 1); $i < $lastNonWhitespace; $i++) {
                        if ($tokens[$i]['line'] === $tokens[$lastNonWhitespace]['line']) {
                            // Keep existing indentation.
                            break;
                        }

                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->addNewline($controlStructureEndPtr);
                    $phpcsFile->fixer->endChangeset();
                }
            }//end if
        } else if ($tokens[$controlStructureEndPtr]['line'] === ($trailingLineNumber - 1)) {
            // Code on the next line after control structure scope closer.
            if ($this->elseOrElseIf($phpcsFile, $trailingContent) === true
                || $this->isCatch($phpcsFile, $trailingContent) === true
            ) {
                return;
            }

            $error = 'No blank line found after "%s" control structure';
            $data  = array($tokens[$stackPtr]['content']);
            $fix   = $phpcsFile->addFixableError($error, $scopeCloser, 'NoLineAfterClose', $data);

            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addNewline($controlStructureEndPtr);
                $phpcsFile->fixer->endChangeset();
            }
        }//end if

    }//end checkTrailingContent()


    /**
     * Returns scope closer  with special check for "do...while" statements.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return int|bool
     */
    protected function getScopeCloser(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $scopeCloser = $tokens[$stackPtr]['scope_closer'];

        if ($tokens[$stackPtr]['code'] !== T_DO) {
            return $scopeCloser;
        }

        $trailingContent = $phpcsFile->findNext(
            Tokens::$emptyTokens,
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
     * Returns trailing content token.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return int|bool
     */
    protected function getTrailingContent(File $phpcsFile, $stackPtr)
    {
        $nextNonWhitespace = $phpcsFile->findNext(
            array(
             T_WHITESPACE,
             T_COMMENT,
            ),
            ($stackPtr + 1),
            null,
            true
        );

        return $nextNonWhitespace;

    }//end getTrailingContent()


    /**
     * Returns trailing comment or self.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool|int
     */
    protected function getTrailingCommentOrSelf(File $phpcsFile, $stackPtr)
    {
        $nextTokens = array($stackPtr);
        $tokens = $phpcsFile->getTokens();

        do {
            $next    = end($nextTokens);
            $newNext = $phpcsFile->findNext(
                T_WHITESPACE,
                ($next + 1),
                null,
                true
            );

            if ($tokens[$newNext]['code'] === T_COMMENT
                && $tokens[$newNext]['line'] === ($tokens[$next]['line'] + 1)
            ) {
                $nextTokens[] = $newNext;
            } else {
                break;
            }
        } while (true);

        return end($nextTokens);

    }//end getTrailingCommentOrSelf()


    /**
     * Finds first token on a line.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $start     Start from token.
     *
     * @return int | bool
     */
    public function findFirstOnLine(File $phpcsFile, $start)
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
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function insideSwitchCase(File $phpcsFile, $stackPtr)
    {
        if ($this->isScopeCondition($phpcsFile, $stackPtr, array(T_CASE, T_DEFAULT)) === true) {
            $tokens = $phpcsFile->getTokens();

            // Consider "return" instead of "break" as function ending to enforce empty line before it.
            return $tokens[$stackPtr]['code'] !== T_RETURN;
        }

        return false;

    }//end insideSwitchCase()


    /**
     * Detects, that it is a closing brace of IF/ELSEIF.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function ifOrElseIf(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_IF, T_ELSEIF));

    }//end ifOrElseIf()


    /**
     * Detects, that it is a closing brace of ELSE/ELSEIF.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function elseOrElseIf(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_ELSE, T_ELSEIF));

    }//end elseOrElseIf()


    /**
     * Detects, that it is a closing brace of TRY/CATCH.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isTryOrCatch(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, array(T_TRY, T_CATCH));

    }//end isTryOrCatch()


    /**
     * Detects, that it is a closing brace of TRY.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isTry(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_TRY);

    }//end isTry()


    /**
     * Detects, that it is a closing brace of CATCH.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isCatch(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_CATCH);

    }//end isCatch()


    /**
     * Determines that a function is located at given position.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return bool
     */
    protected function isFunction(File $phpcsFile, $stackPtr)
    {
        return $this->isScopeCondition($phpcsFile, $stackPtr, T_FUNCTION);

    }//end isFunction()


    /**
     * Determines that a closure is located at given position.
     *
     * @param File $phpcsFile         The file being scanned.
     * @param int  $stackPtr          The position of the current token.
     *                                in the stack passed in $tokens.
     * @param int  $scopeConditionPtr Position of scope condition.
     *
     * @return bool
     */
    protected function isClosure(File $phpcsFile, $stackPtr, $scopeConditionPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if ($this->isScopeCondition($phpcsFile, $scopeConditionPtr, T_CLOSURE) === true
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
     * @param File      $phpcsFile The file being scanned.
     * @param int       $stackPtr  The position of the current token
     *                             in the stack passed in $tokens.
     * @param int|array $types     The type(s) of tokens to search for.
     *
     * @return bool
     */
    protected function isScopeCondition(File $phpcsFile, $stackPtr, $types)
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

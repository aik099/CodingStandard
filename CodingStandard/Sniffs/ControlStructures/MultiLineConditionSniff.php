<?php
/**
 * CodingStandard_Sniffs_ControlStructures_MultiLineConditionSniff.
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
if (class_exists('PEAR_Sniffs_ControlStructures_MultiLineConditionSniff', true) === false) {
    $error = 'Class PEAR_Sniffs_ControlStructures_MultiLineConditionSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * CodingStandard_Sniffs_ControlStructures_MultiLineConditionSniff.
 *
 * Ensure single and multi-line function declarations are defined correctly.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_ControlStructures_MultiLineConditionSniff extends PEAR_Sniffs_ControlStructures_MultiLineConditionSniff
{

    /**
     * Should tabs be used for indenting?
     *
     * If TRUE, fixes will be made using tabs instead of spaces.
     * The size of each tab is important, so it should be specified
     * using the --tab-width CLI argument.
     *
     * @var bool
     */
    public $tabIndent = false;

    /**
     * The --tab-width CLI value that is being used.
     *
     * @var int
     */
    private $_tabWidth = null;

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        if ($this->_tabWidth === null) {
            $cliValues = $phpcsFile->phpcs->cli->getCommandLineValues();
            if (isset($cliValues['tabWidth']) === false || $cliValues['tabWidth'] === 0) {
                // We have no idea how wide tabs are, so assume 4 spaces for fixing.
                // It shouldn't really matter because indent checks elsewhere in the
                // standard should fix things up.
                $this->_tabWidth = 4;
            } else {
                $this->_tabWidth = $cliValues['tabWidth'];
            }
        }

        $this->tabIndent = (bool)$this->tabIndent;

        $tokens = $phpcsFile->getTokens();

        // We need to work out how far indented the if statement
        // itself is, so we can work out how far to indent conditions.
        $statementIndent = 0;
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                $i++;
                break;
            }
        }

        if ($i >= 0 && $tokens[$i]['code'] === T_WHITESPACE) {
            $statementIndent = strlen($tokens[$i]['content']);
        }

        // Each line between the parenthesis should be indented 4 spaces
        // and start with an operator, unless the line is inside a
        // function call, in which case it is ignored.
        $openBracket  = $tokens[$stackPtr]['parenthesis_opener'];
        $closeBracket = $tokens[$stackPtr]['parenthesis_closer'];
        $lastLine     = $tokens[$openBracket]['line'];
        for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
            if ($tokens[$i]['line'] !== $lastLine) {
                if ($tokens[$i]['line'] === $tokens[$closeBracket]['line']) {
                    $next = $phpcsFile->findNext(T_WHITESPACE, $i, null, true);
                    if ($next !== $closeBracket) {
                        // Closing bracket is on the same line as a condition.
                        $error = 'Closing parenthesis of a multi-line IF statement must be on a new line';
                        $fix   = $phpcsFile->addFixableError($error, $closeBracket, 'CloseBracketNewLine');
                        if ($fix === true) {
                            $phpcsFile->fixer->addNewlineBefore($closeBracket);
                        }

                        $expectedIndent = ($statementIndent + $this->indent);
                    } else {
                        // Closing brace needs to be indented to the same level
                        // as the statement.
                        $expectedIndent = $statementIndent;
                    }
                } else {
                    $expectedIndent = ($statementIndent + $this->indent);
                }

                // We changed lines, so this should be a whitespace indent token.
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $foundIndent = 0;
                } else {
                    $foundIndent = strlen($tokens[$i]['content']);
                }

                if ($expectedIndent !== $foundIndent) {
                    $error = 'Multi-line IF statement not indented correctly; expected %s spaces but found %s';
                    $data  = array(
                              $expectedIndent,
                              $foundIndent,
                             );

                    $fix = $phpcsFile->addFixableError($error, $i, 'Alignment', $data);
                    if ($fix === true) {
                        $spaces = $this->createPadding($expectedIndent); // CUSTOM.
                        if ($foundIndent === 0) {
                            $phpcsFile->fixer->addContentBefore($i, $spaces);
                        } else {
                            $phpcsFile->fixer->replaceToken($i, $spaces);
                        }
                    }
                }

                if ($tokens[$i]['line'] !== $tokens[$closeBracket]['line']) {
                    $next = $phpcsFile->findNext(T_WHITESPACE, $i, null, true);
                    if (isset(PHP_CodeSniffer_Tokens::$booleanOperators[$tokens[$next]['code']]) === false) {
                        $error = 'Each line in a multi-line IF statement must begin with a boolean operator';
                        $fix   = $phpcsFile->addFixableError($error, $i, 'StartWithBoolean');
                        if ($fix === true) {
                            $prev = $phpcsFile->findPrevious(T_WHITESPACE, ($i - 1), $openBracket, true);
                            $phpcsFile->fixer->beginChangeset();
                            for ($x = ($prev + 1); $x < $next; $x++) {
                                $phpcsFile->fixer->replaceToken($x, '');
                            }

                            if (isset(PHP_CodeSniffer_Tokens::$booleanOperators[$tokens[$prev]['code']]) === true) {
                                $phpcsFile->fixer->addNewline($prev - 1);
                                $phpcsFile->fixer->addContent($prev, ' ');
                                $phpcsFile->fixer->addContentBefore($prev, $this->createPadding($expectedIndent)); // CUSTOM.
                            }

                            $phpcsFile->fixer->endChangeset();
                        }
                    }
                }//end if

                $lastLine = $tokens[$i]['line'];
            }//end if

            if ($tokens[$i]['code'] === T_STRING) {
                $next = $phpcsFile->findNext(T_WHITESPACE, ($i + 1), null, true);
                if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS) {
                    // This is a function call, so skip to the end as they
                    // have their own indentation rules.
                    $i        = $tokens[$next]['parenthesis_closer'];
                    $lastLine = $tokens[$i]['line'];
                    continue;
                }
            }
        }//end for

        // From here on, we are checking the spacing of the opening and closing
        // braces. If this IF statement does not use braces, we end here.
        if (isset($tokens[$stackPtr]['scope_opener']) === false) {
            return;
        }

        // The opening brace needs to be one space away from the closing parenthesis.
        if ($tokens[($closeBracket + 1)]['code'] !== T_WHITESPACE) {
            $length = 0;
        } else if ($tokens[($closeBracket + 1)]['content'] === $phpcsFile->eolChar) {
            $length = -1;
        } else {
            $length = strlen($tokens[($closeBracket + 1)]['content']);
        }

        if ($length !== 1) {
            $data = array($length);
            $code = 'SpaceBeforeOpenBrace';

            $error = 'There must be a single space between the closing parenthesis and the opening brace of a multi-line IF statement; found ';
            if ($length === -1) {
                $error .= 'newline';
                $code   = 'NewlineBeforeOpenBrace';
            } else {
                $error .= '%s spaces';
            }

            $fix = $phpcsFile->addFixableError($error, ($closeBracket + 1), $code, $data);
            if ($fix === true) {
                if ($length === 0) {
                    $phpcsFile->fixer->addContent($closeBracket, ' ');
                } else {
                    $phpcsFile->fixer->replaceToken(($closeBracket + 1), ' ');
                }
            }
        }//end if

        // And just in case they do something funny before the brace...
        $next = $phpcsFile->findNext(T_WHITESPACE, ($closeBracket + 1), null, true);
        if ($next !== false
            && $tokens[$next]['code'] !== T_OPEN_CURLY_BRACKET
            && $tokens[$next]['code'] !== T_COLON
        ) {
            $error = 'There must be a single space between the closing parenthesis and the opening brace of a multi-line IF statement';
            $phpcsFile->addError($error, $next, 'NoSpaceBeforeOpenBrace');
        }
    }


    /**
     * Creates padding of needed length.
     *
     * @param int $padding Padding length.
     *
     * @return string
     */
    protected function createPadding($padding)
    {
        if ($this->tabIndent === true) {
            $numTabs = floor($padding / $this->_tabWidth);
            $numSpaces = ($padding - ($numTabs * $this->_tabWidth));

            return str_repeat("\t", $numTabs).str_repeat(' ', $numSpaces);
        }

        return str_repeat(' ', $padding);
    }//end createPadding()





}//end class

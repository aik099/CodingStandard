<?php
/**
 * CodingStandard_Sniffs_Functions_FunctionCallSignatureSniff.
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
if (class_exists('PSR2_Sniffs_Methods_FunctionCallSignatureSniff', true) === false) {
    $error = 'Class PSR2_Sniffs_Methods_FunctionCallSignatureSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * CodingStandard_Sniffs_Functions_FunctionCallSignatureSniff.
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
class CodingStandard_Sniffs_Functions_FunctionCallSignatureSniff extends PSR2_Sniffs_Methods_FunctionCallSignatureSniff
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

        parent::process($phpcsFile, $stackPtr);
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


    /**
     * Processes multi-line calls.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the current token
     *                                          in the stack passed in $tokens.
     * @param int                  $openBracket The position of the openning bracket
     *                                          in the stack passed in $tokens.
     * @param array                $tokens      The stack of tokens that make up
     *                                          the file.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function processMultiLineCall(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $openBracket, $tokens)
    {
        $this->processMultiLineCallPSR2($phpcsFile, $stackPtr, $openBracket, $tokens);

    }//end processMultiLineCall()


    /**
     * Processes multi-line calls.
     *
     * @param PHP_CodeSniffer_File $phpcsFile   The file being scanned.
     * @param int                  $stackPtr    The position of the current token
     *                                          in the stack passed in $tokens.
     * @param int                  $openBracket The position of the openning bracket
     *                                          in the stack passed in $tokens.
     * @param array                $tokens      The stack of tokens that make up
     *                                          the file.
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    public function processMultiLineCallPSR2(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $openBracket, $tokens)
    {
        // We need to work out how far indented the function
        // call itself is, so we can work out how far to
        // indent the arguments.
        $functionIndent = 0;
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                $i++;
                break;
            }
        }

        if ($i > 0 && $tokens[$i]['code'] === T_WHITESPACE) {
            $functionIndent = strlen($tokens[$i]['content']);
        }

        if ($tokens[($openBracket + 1)]['content'] !== $phpcsFile->eolChar) {
            $error = 'Opening parenthesis of a multi-line function call must be the last content on the line';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'ContentAfterOpenBracket');
            if ($fix === true) {
                $phpcsFile->fixer->addContent(
                    $openBracket,
                    $phpcsFile->eolChar.$this->createPadding(($functionIndent + $this->indent)) // CUSTOM.
                );
            }
        }

        $closeBracket = $tokens[$openBracket]['parenthesis_closer'];
        $prev         = $phpcsFile->findPrevious(T_WHITESPACE, ($closeBracket - 1), null, true);
        if ($tokens[$prev]['line'] === $tokens[$closeBracket]['line']) {
            $error = 'Closing parenthesis of a multi-line function call must be on a line by itself';
            $fix   = $phpcsFile->addFixableError($error, $closeBracket, 'CloseBracketLine');
            if ($fix === true) {
                $phpcsFile->fixer->addContentBefore(
                    $closeBracket,
                    $phpcsFile->eolChar.$this->createPadding(($functionIndent + $this->indent)) // CUSTOM.
                );
            }
        }

        // Each line between the parenthesis should be indented n spaces.
        $lastLine = $tokens[$openBracket]['line'];
        $exact    = true;
        $exactEnd = null;
        for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
            if ($i === $exactEnd) {
                $exact = true;
            }

            if ($tokens[$i]['line'] !== $lastLine) {
                $lastLine = $tokens[$i]['line'];

                // Ignore heredoc indentation.
                if (isset(PHP_CodeSniffer_Tokens::$heredocTokens[$tokens[$i]['code']]) === true) {
                    continue;
                }

                // Ignore multi-line string indentation.
                if (isset(PHP_CodeSniffer_Tokens::$stringTokens[$tokens[$i]['code']]) === true) {
                    if ($tokens[$i]['code'] === $tokens[($i - 1)]['code']) {
                        continue;
                    }
                }

                // We changed lines, so this should be a whitespace indent token, but first make
                // sure it isn't a blank line because we don't need to check indent unless there
                // is actually some code to indent.
                if ($tokens[$i]['code'] === T_WHITESPACE) {
                    $nextCode = $phpcsFile->findNext(T_WHITESPACE, ($i + 1), ($closeBracket + 1), true);
                    if ($tokens[$nextCode]['line'] !== $lastLine) {
                        if ($exact === true) {
                            $error = 'Empty lines are not allowed in multi-line function calls';
                            $fix   = $phpcsFile->addFixableError($error, $i, 'EmptyLine');
                            if ($fix === true) {
                                $phpcsFile->fixer->replaceToken($i, '');
                            }
                        }

                        continue;
                    }
                } else {
                    $nextCode = $i;
                }

                // Check if the next line contains an object operator, if so rely on
                // the ObjectOperatorIndentSniff to test the indent.
                if ($tokens[$nextCode]['type'] === 'T_OBJECT_OPERATOR') {
                    continue;
                }

                if ($tokens[$nextCode]['line'] === $tokens[$closeBracket]['line']) {
                    // Closing brace needs to be indented to the same level
                    // as the function call.
                    $expectedIndent = $functionIndent;
                } else {
                    $expectedIndent = ($functionIndent + $this->indent);
                }

                if ($tokens[$i]['code'] !== T_WHITESPACE
                    && $tokens[$i]['code'] !== T_DOC_COMMENT_WHITESPACE
                ) {
                    // Just check if it is a multi-line block comment. If so, we can
                    // calculate the indent from the whitespace before the content.
                    if ($tokens[$i]['code'] === T_COMMENT
                        && $tokens[($i - 1)]['code'] === T_COMMENT
                    ) {
                        $trimmed     = ltrim($tokens[$i]['content']);
                        $foundIndent = (strlen($tokens[$i]['content']) - strlen($trimmed));
                    } else {
                        $foundIndent = 0;
                    }
                } else {
                    $foundIndent = strlen($tokens[$i]['content']);
                }

                if ($foundIndent < $expectedIndent
                    || ($exact === true
                    && $expectedIndent !== $foundIndent)
                ) {
                    $error = 'Multi-line function call not indented correctly; expected %s spaces but found %s';
                    $data  = array(
                              $expectedIndent,
                              $foundIndent,
                             );

                    $fix = $phpcsFile->addFixableError($error, $i, 'Indent', $data);
                    if ($fix === true) {
                        $padding = $this->createPadding($expectedIndent); // CUSTOM.
                        if ($foundIndent === 0) {
                            $phpcsFile->fixer->addContentBefore($i, $padding);
                        } else {
                            if ($tokens[$i]['code'] === T_COMMENT) {
                                $comment = $padding.ltrim($tokens[$i]['content']);
                                $phpcsFile->fixer->replaceToken($i, $comment);
                            } else {
                                $phpcsFile->fixer->replaceToken($i, $padding);
                            }
                        }
                    }
                }//end if
            }//end if

            // Turn off exact indent matching for some structures that typically
            // define their own indentation rules.
            if ($exact === true) {
                if ($tokens[$i]['code'] === T_CLOSURE) {
                    $exact    = false;
                    $exactEnd = $tokens[$i]['scope_closer'];
                } else if ($tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
                    $exact    = false;
                    $exactEnd = $tokens[$i]['bracket_closer'];
                } else if ($tokens[$i]['code'] === T_DOC_COMMENT_OPEN_TAG) {
                    $exact    = false;
                    $exactEnd = $tokens[$i]['comment_closer'];
                } else if ($tokens[$i]['code'] === T_OPEN_PARENTHESIS) {
                    $exact    = false;
                    $exactEnd = $tokens[$i]['parenthesis_closer'];
                } else if ($phpcsFile->tokenizerType === 'JS'
                    && $tokens[$i]['code'] === T_OBJECT
                ) {
                    $exact    = false;
                    $exactEnd = $tokens[$i]['bracket_closer'];
                }
            } else {
                continue;
            }//end if

            if ($this->allowMultipleArguments === false && $tokens[$i]['code'] === T_COMMA) {
                // Comma has to be the last token on the line.
                $next = $phpcsFile->findNext(array(T_WHITESPACE, T_COMMENT), ($i + 1), $closeBracket, true);
                if ($next !== false
                    && $tokens[$i]['line'] === $tokens[$next]['line']
                ) {
                    $error = 'Only one argument is allowed per line in a multi-line function call';
                    $fix   = $phpcsFile->addFixableError($error, $next, 'MultipleArguments');
                    if ($fix === true) {
                        $phpcsFile->fixer->beginChangeset();
                        for ($x = ($next - 1); $x > $i; $x--) {
                            if ($tokens[$x]['code'] !== T_WHITESPACE) {
                                break;
                            }

                            $phpcsFile->fixer->replaceToken($x, '');
                        }

                        $phpcsFile->fixer->addContentBefore(
                            $next,
                            $phpcsFile->eolChar.$this->createPadding(($functionIndent + $this->indent)) // CUSTOM.
                        );
                        $phpcsFile->fixer->endChangeset();
                    }
                }
            }//end if
        }//end for

    }//end processMultiLineCallPSR2()


}//end class

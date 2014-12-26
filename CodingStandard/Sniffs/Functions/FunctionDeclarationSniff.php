<?php
/**
 * CodingStandard_Sniffs_Functions_FunctionDeclarationSniff.
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
if (class_exists('PEAR_Sniffs_Functions_FunctionDeclarationSniff', true) === false) {
    $error = 'Class PEAR_Sniffs_Functions_FunctionDeclarationSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * CodingStandard_Sniffs_Functions_FunctionDeclarationSniff.
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
class CodingStandard_Sniffs_Functions_FunctionDeclarationSniff extends PEAR_Sniffs_Functions_FunctionDeclarationSniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
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

        parent::process($phpcsFile, $stackPtr);

    }//end process()


    /**
     * Processes mutli-line declarations.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param array                $tokens    The stack of tokens that make up
     *                                        the file.
     *
     * @return void
     */
    public function processMultiLineDeclaration(PHP_CodeSniffer_File $phpcsFile, $stackPtr, $tokens)
    {
        // We need to work out how far indented the function
        // declaration itself is, so we can work out how far to
        // indent parameters.
        $functionIndent = 0;
        for ($i = ($stackPtr - 1); $i >= 0; $i--) {
            if ($tokens[$i]['line'] !== $tokens[$stackPtr]['line']) {
                $i++;
                break;
            }
        }

        if ($tokens[$i]['code'] === T_WHITESPACE) {
            $functionIndent = $tokens[$i]['length'];
        }

        // The closing parenthesis must be on a new line, even
        // when checking abstract function definitions.
        $closeBracket = $tokens[$stackPtr]['parenthesis_closer'];
        $prev         = $phpcsFile->findPrevious(
            T_WHITESPACE,
            ($closeBracket - 1),
            null,
            true
        );

        if ($tokens[$closeBracket]['line'] !== $tokens[$tokens[$closeBracket]['parenthesis_opener']]['line']) {
            if ($tokens[$prev]['line'] === $tokens[$closeBracket]['line']) {
                $error = 'The closing parenthesis of a multi-line function declaration must be on a new line';
                $fix   = $phpcsFile->addFixableError($error, $closeBracket, 'CloseBracketLine');
                if ($fix === true) {
                    $phpcsFile->fixer->addNewlineBefore($closeBracket);
                }
            }
        }

        // If this is a closure and is using a USE statement, the closing
        // parenthesis we need to look at from now on is the closing parenthesis
        // of the USE statement.
        if ($tokens[$stackPtr]['code'] === T_CLOSURE) {
            $use = $phpcsFile->findNext(T_USE, ($closeBracket + 1), $tokens[$stackPtr]['scope_opener']);
            if ($use !== false) {
                $open         = $phpcsFile->findNext(T_OPEN_PARENTHESIS, ($use + 1));
                $closeBracket = $tokens[$open]['parenthesis_closer'];

                $prev = $phpcsFile->findPrevious(
                    T_WHITESPACE,
                    ($closeBracket - 1),
                    null,
                    true
                );

                if ($tokens[$closeBracket]['line'] !== $tokens[$tokens[$closeBracket]['parenthesis_opener']]['line']) {
                    if ($tokens[$prev]['line'] === $tokens[$closeBracket]['line']) {
                        $error = 'The closing parenthesis of a multi-line use declaration must be on a new line';
                        $fix   = $phpcsFile->addFixableError($error, $closeBracket, 'UseCloseBracketLine');
                        if ($fix === true) {
                            $phpcsFile->fixer->addNewlineBefore($closeBracket);
                        }
                    }
                }
            }//end if
        }//end if

        // Each line between the parenthesis should be indented 4 spaces.
        $openBracket = $tokens[$stackPtr]['parenthesis_opener'];
        $lastLine    = $tokens[$openBracket]['line'];
        for ($i = ($openBracket + 1); $i < $closeBracket; $i++) {
            if ($tokens[$i]['line'] !== $lastLine) {
                if ($i === $tokens[$stackPtr]['parenthesis_closer']
                    || ($tokens[$i]['code'] === T_WHITESPACE
                    && (($i + 1) === $closeBracket
                    || ($i + 1) === $tokens[$stackPtr]['parenthesis_closer']))
                ) {
                    // Closing braces need to be indented to the same level
                    // as the function.
                    $expectedIndent = $functionIndent;
                } else {
                    $expectedIndent = ($functionIndent + $this->indent);
                }

                // We changed lines, so this should be a whitespace indent token.
                if ($tokens[$i]['code'] !== T_WHITESPACE) {
                    $foundIndent = 0;
                } else {
                    $foundIndent = $tokens[$i]['length'];
                }

                if ($expectedIndent !== $foundIndent) {
                    $error = 'Multi-line function declaration not indented correctly; expected %s spaces but found %s';
                    $data  = array(
                              $expectedIndent,
                              $foundIndent,
                             );

                    $fix = $phpcsFile->addFixableError($error, $i, 'Indent', $data);
                    if ($fix === true) {
                        if ($this->tabIndent === true) {
                            $numTabs   = floor($expectedIndent / $this->_tabWidth);
                            $numSpaces = ($expectedIndent - ($numTabs * $this->_tabWidth));
                            $spaces    = str_repeat("\t", $numTabs).str_repeat(' ', $numSpaces);
                        } else {
                            $spaces = str_repeat(' ', $expectedIndent);
                        }

                        if ($foundIndent === 0) {
                            $phpcsFile->fixer->addContentBefore($i, $spaces);
                        } else {
                            $phpcsFile->fixer->replaceToken($i, $spaces);
                        }
                    }
                }//end if

                $lastLine = $tokens[$i]['line'];
            }//end if

            if ($tokens[$i]['code'] === T_ARRAY || $tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
                // Skip arrays as they have their own indentation rules.
                if ($tokens[$i]['code'] === T_OPEN_SHORT_ARRAY) {
                    $i = $tokens[$i]['bracket_closer'];
                } else {
                    $i = $tokens[$i]['parenthesis_closer'];
                }

                $lastLine = $tokens[$i]['line'];
                continue;
            }
        }//end for

    }//end processMultiLineDeclaration()


}//end class

<?php
/**
 * CodingStandard_Sniffs_NamingConventions_ValidVariableNameSniff.
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

// @codeCoverageIgnoreStart
if (class_exists('PHP_CodeSniffer_Standards_AbstractVariableSniff', true) === false) {
    $error = 'Class PHP_CodeSniffer_Standards_AbstractVariableSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * CodingStandard_Sniffs_NamingConventions_ValidVariableNameSniff.
 *
 * Checks the naming of variables and member variables.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_NamingConventions_ValidVariableNameSniff extends
 PHP_CodeSniffer_Standards_AbstractVariableSniff
{

    /**
     * Variable names, that are reserved in PHP.
     *
     * @var array
     */
    protected $phpReservedVars = array(
                                  '_SERVER',
                                  '_GET',
                                  '_POST',
                                  '_REQUEST',
                                  '_SESSION',
                                  '_ENV',
                                  '_COOKIE',
                                  '_FILES',
                                  'GLOBALS',
                                  'http_response_header',
                                  'HTTP_RAW_POST_DATA',
                                  'php_errormsg',
                                 );

    /**
     * Member variable names that break the rules, but are allowed.
     *
     * @var array
     */
    protected $memberExceptions = array(
                                   // From "kBase".
                                   'Application',
                                   'Conn',

                                   // From "kEvent".
                                   'Name',
                                   'MasterEvent',
                                   'Prefix',
                                   'Special',

                                   // From "kDBItem".
                                   'IDField',
                                   'TableName',
                                   'IgnoreValidation',
                                  );


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens  = $phpcsFile->getTokens();
        $varName = ltrim($tokens[$stackPtr]['content'], '$');

        // If it's a php reserved var, then its ok.
        if (in_array($varName, $this->phpReservedVars) === true) {
            return;
        }

        $objOperator = $phpcsFile->findNext(array(T_WHITESPACE), ($stackPtr + 1), null, true);
        if ($tokens[$objOperator]['code'] === T_OBJECT_OPERATOR) {
            // Check to see if we are using a variable from an object.
            $var = $phpcsFile->findNext(array(T_WHITESPACE), ($objOperator + 1), null, true);
            if ($tokens[$var]['code'] === T_STRING) {
                // Either a var name or a function call, so check for bracket.
                $bracket = $phpcsFile->findNext(array(T_WHITESPACE), ($var + 1), null, true);
                if ($tokens[$bracket]['code'] !== T_OPEN_PARENTHESIS) {
                    $objVarName = $tokens[$var]['content'];

                    // There is no way for us to know if the var is public or private,
                    // so we have to ignore a leading underscore if there is one and just
                    // check the main part of the variable name.
                    $originalVarName = $objVarName;
                    if (substr($objVarName, 0, 1) === '_') {
                        $objVarName = substr($objVarName, 1);
                    }

                    if ($this->isCamelCaps($objVarName) === false) {
                        $error = 'Member variable "%s" is not in valid camel caps format';
                        $data  = array($originalVarName);
                        $phpcsFile->addError($error, $var, 'MemberNotCamelCaps', $data);
                    }
                }//end if
            }//end if
        }//end if

        // There is no way for us to know if the var is public or private,
        // so we have to ignore a leading underscore if there is one and just
        // check the main part of the variable name.
        $originalVarName = $varName;
        $objOperator     = $phpcsFile->findPrevious(array(T_WHITESPACE), ($stackPtr - 1), null, true);

        if ($tokens[$objOperator]['code'] === T_DOUBLE_COLON) {
            // The variable lives within a class, and is referenced like
            // this: MyClass::$_variable, so we don't know its scope.
            $inClass = true;
        } else {
            $inClass = false;
        }

        if ($inClass === true && substr($varName, 0, 1) === '_') {
            $varName = substr($varName, 1);
        }

        if ($inClass === true && $this->isCamelCaps($varName) === false) {
            $error = 'Variable "%s" is not in valid camel caps format';
            $data  = array($originalVarName);
            $phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
        } else if ($inClass === false && $this->isSnakeCaps($varName) === false) {
            $error = 'Variable "%s" is not in valid snake caps format';
            $data  = array($originalVarName);
            $phpcsFile->addError($error, $stackPtr, 'NotSnakeCaps', $data);
        }

    }//end processVariable()


    /**
     * Processes class member variables.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $varName     = ltrim($tokens[$stackPtr]['content'], '$');
        $memberProps = $phpcsFile->getMemberProperties($stackPtr);

        // @codeCoverageIgnoreStart
        if (empty($memberProps) === true) {
            // Couldn't get any info about this variable, which
            // generally means it is invalid or possibly has a parse
            // error. Any errors will be reported by the core, so
            // we can ignore it.
            return;
        }
        // @codeCoverageIgnoreEnd

        $public    = ($memberProps['scope'] !== 'private');
        $errorData = array($varName);

        if ($public === true) {
            if (substr($varName, 0, 1) === '_') {
                $error = '%s member variable "%s" must not contain a leading underscore';
                $data  = array(
                          ucfirst($memberProps['scope']),
                          $errorData[0],
                         );
                $phpcsFile->addError($error, $stackPtr, 'PublicHasUnderscore', $data);
                return;
            }
        } else {
            if (substr($varName, 0, 1) !== '_') {
                $error = 'Private member variable "%s" must contain a leading underscore';
                $phpcsFile->addError($error, $stackPtr, 'PrivateNoUnderscore', $errorData);
                return;
            }
        }

        if ($this->isCamelCaps($varName, $public) === false) {
            $error = '%s member variable "%s" is not in valid camel caps format';
            $data  = array(
                      ucfirst($memberProps['scope']),
                      $errorData[0],
                     );
            $phpcsFile->addError($error, $stackPtr, 'MemberNotCamelCaps', $data);
        }

    }//end processMemberVar()


    /**
     * Processes the variable found within a double quoted string.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the double quoted
     *                                        string.
     *
     * @return void
     */
    protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        if (preg_match_all('|[^\\\]\${?([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)|', $tokens[$stackPtr]['content'], $matches) !== 0) {
            foreach ($matches[1] as $varName) {
                // If it's a php reserved var, then its ok.
                if (in_array($varName, $this->phpReservedVars) === true) {
                    continue;
                }

                // There is no way for us to know if the var is public or private,
                // so we have to ignore a leading underscore if there is one and just
                // check the main part of the variable name.
                $originalVarName = $varName;
                $inClass         = $this->withinClass($phpcsFile, $stackPtr);

                if ($inClass === true && substr($varName, 0, 1) === '_') {
                    $varName = substr($varName, 1);
                }

                if ($inClass === true && $this->isCamelCaps($varName) === false) {
                    $error = 'Variable in string "%s" is not in valid camel caps format';
                    $data  = array($originalVarName);
                    $phpcsFile->addError($error, $stackPtr, 'StringNotCamelCaps', $data);
                } else if ($inClass === false && $this->isSnakeCaps($varName) === false) {
                    $error = 'Variable in string "%s" is not in valid snake caps format';
                    $data  = array($originalVarName);
                    $phpcsFile->addError($error, $stackPtr, 'StringNotSnakeCaps', $data);
                }
            }//end foreach
        }//end if

    }//end processVariableInString()


    /**
     * Determines if a variable is in camel caps case.
     *
     * @param string $string String.
     * @param bool   $public If true, the first character in the string
     *                       must be an a-z character. If false, the
     *                       character must be an underscore. This
     *                       argument is only applicable if $classFormat
     *                       is false.
     *
     * @return bool
     */
    protected function isCamelCaps($string, $public=true)
    {
        if (in_array($string, $this->memberExceptions) === true) {
            return true;
        }

        return PHP_CodeSniffer::isCamelCaps($string, false, $public, false);

    }//end isCamelCaps()


    /**
     * Determines if a variable is in snake caps case.
     *
     * @param string $string String.
     *
     * @return bool
     */
    protected function isSnakeCaps($string)
    {
        return strtolower($string) === $string;

    }//end isSnakeCaps()


    /**
     * Determines if variable (at pointer) is within a class/interface/trait.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return bool
     */
    protected function withinClass(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        return $phpcsFile->hasCondition($stackPtr, array(T_CLASS, T_INTERFACE, T_TRAIT));

    }//end withinClass()


}//end class

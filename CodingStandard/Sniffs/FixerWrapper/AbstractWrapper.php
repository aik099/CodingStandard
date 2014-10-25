<?php
/**
 * CodingStandard_Sniffs_FixerWrapper_AbstractWrapper.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * CodingStandard_Sniffs_FixerWrapper_AbstractWrapper.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
abstract class CodingStandard_Sniffs_FixerWrapper_AbstractWrapper
{

    /**
     * The file being scanned.
     *
     * @var PHP_CodeSniffer_File
     */
    protected $phpcsFile;


    /**
     * Associates file with a fixer.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     */
    public function __construct(PHP_CodeSniffer_File $phpcsFile)
    {
        $this->phpcsFile = $phpcsFile;

    }//end __construct()


    /**
     * Records a fixable error against a specific token in the file.
     *
     * Returns true if the error was recorded and should be fixed.
     *
     * @param string $error    The error message.
     * @param int    $stackPtr The stack position where the error occurred.
     * @param string $code     A violation code unique to the sniff message.
     * @param array  $data     Replacements for the error message.
     * @param int    $severity The severity level for this error. A value of 0
     *                         will be converted into the default severity level.
     *
     * @return boolean
     */
    abstract public function addFixableError($error, $stackPtr, $code='', $data=array(), $severity=0);


    /**
     * Records a fixable warning against a specific token in the file.
     *
     * Returns true if the warning was recorded and should be fixed.
     *
     * @param string $warning  The error message.
     * @param int    $stackPtr The stack position where the error occurred.
     * @param string $code     A violation code unique to the sniff message.
     * @param array  $data     Replacements for the warning message.
     * @param int    $severity The severity level for this warning. A value of 0
     *                         will be converted into the default severity level.
     *
     * @return boolean
     */
    abstract public function addFixableWarning($warning, $stackPtr, $code='', $data=array(), $severity=0);


}//end class

?>

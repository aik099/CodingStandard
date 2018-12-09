<?php
/**
 * CodingStandard_Tests_Classes_ClassNamespaceUnitTest.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Tests\Classes;

use TestSuite\AbstractSniffUnitTest;

/**
 * Unit test class for the ClassNamespace sniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class ClassNamespaceUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile Name of the file with test data.
     *
     * @return array(int => int)
     */
    public function getErrorList($testFile)
    {
        // Compensate for https://github.com/squizlabs/PHP_CodeSniffer/issues/1169 fix.
        return array(2 => 1);
    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile Name of the file with test data.
     *
     * @return array(int => int)
     */
    public function getWarningList($testFile)
    {
        return array();
    }//end getWarningList()
}//end class

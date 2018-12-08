<?php
/**
 * CodingStandard_Tests_Array_ArrayUnitTest.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * Unit test class for the Array sniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Tests_Arrays_ArrayUnitTest extends AbstractSniffUnitTest
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
        return array(
                // Space after "array" keyword.
                2  => 1,
                // Malformed empty array.
                3  => 1,
                5  => 1,
                // Space after opening array brace.
                10 => 1,
                // Space before closing array brace.
                11 => 1,
               );

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
        return array(
                // No comma after multi-line array last element.
                7 => 1,
                // Comma after last element of single-line array.
                9 => 1,
                // Space after last element (nested multi-line array).
                14 => 1,
                // Space after last element (multi-line array).
                18 => 1,
                // Space after last element (nested multi-line array).
                19 => 1,
                // No comma after last element followed by comment on next line.
                22 => 1,
               );

    }//end getWarningList()


}//end class

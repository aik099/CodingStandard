<?php
/**
 * CodingStandard_Tests_Formatting_SpaceOperatorUnitTest.
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
 * Unit test class for the SpaceOperator sniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Tests_Formatting_SpaceOperatorUnitTest extends AbstractSniffUnitTest
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
                // The "=" operator.
                2  => 2,
                // The "&=" operator.
                3  => 2,
                // The ".=" operator.
                4  => 2,
                // The "/=" operator.
                5  => 2,
                // The "-=" operator.
                6  => 2,
                // The "%=" operator.
                7  => 2,
                // The "*=" operator.
                8  => 2,
                // The "+=" operator.
                9  => 2,
                // The "^=" operator.
                10 => 2,
                // The "=>" operator.
                11 => 2,
                // The "==" operator.
                12 => 2,
                // The "===" operator.
                13 => 2,
                // The "!=" operator.
                14 => 2,
                // The "<>" operator.
                15 => 2,
                // The "!==" operator.
                16 => 2,
                // The "<" operator.
                17 => 2,
                // The ">" operator.
                18 => 2,
                // The "<=" operator.
                19 => 2,
                // The "<=" operator.
                20 => 2,
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
        return array();

    }//end getWarningList()


}//end class

?>

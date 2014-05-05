<?php
/**
 * CodingStandard_Tests_ControlStructures_ControlSignatureUnitTest.
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
 * Unit test class for the ControlSignature sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Tests_ControlStructures_ControlSignatureUnitTest extends AbstractSniffUnitTest
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
                // The "do ... while" construct.
                5   => 1,
                7   => 1,
                10  => 1,
                12  => 1,
                14  => 1,
                17  => 1,
                // The "while" construct.
                23  => 1,
                25  => 1,
                27  => 1,
                // The "switch" construct.
                33  => 1,
                35  => 1,
                37  => 1,
                // The "for" construct.
                43  => 1,
                45  => 1,
                47  => 1,
                // The "if" construct.
                53  => 1,
                55  => 1,
                57  => 1,
                // The "foreach" construct.
                63  => 1,
                65  => 1,
                67  => 1,
                // The "elseif" construct.
                76  => 1,
                80  => 1,
                84  => 1,
                88  => 1,
                // The "else" construct.
                97  => 1,
                101 => 1,
                105 => 1,
                // The "do" construct.
                111 => 1,
                113 => 1,
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

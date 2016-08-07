<?php
/**
 * Unit test class for TypeCommentSniff.
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
 * Unit test class for TypeCommentSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Tests_Commenting_TypeCommentUnitTest extends AbstractSniffUnitTest
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
        if ($testFile === 'TypeCommentUnitTest.1.inc') {
            return array(
                    15 => 1,
                    18 => 1,
                    32 => 1,
                    35 => 1,
                    38 => 3,
                    41 => 1,
                    44 => 1,
                    47 => 1,
                    53 => 1,
                    56 => 1,
                    59 => 1,
                    62 => 1,
                   );
        } elseif ($testFile === 'TypeCommentUnitTest.2.inc') {
            return array(
                    10 => 1,
                    13 => 1,
                    18 => 1,
                    23 => 1,
                    28 => 1,
                    34 => 1,
                    42 => 1,
                    50 => 1,
                    53 => 1,
                    56 => 1,
                    60 => 1,
                    64 => 1,
                   );
        }

        return array();

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

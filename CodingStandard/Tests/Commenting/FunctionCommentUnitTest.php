<?php
/**
 * CodingStandard_Tests_Commenting_FunctionCommentUnitTest.
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

namespace CodingStandard\Tests\Commenting;

use TestSuite\AbstractSniffUnitTest;

/**
 * Unit test class for FunctionCommentSniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class FunctionCommentUnitTest extends AbstractSniffUnitTest
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
        if ($testFile === 'FunctionCommentUnitTest.1.inc') {
            $ret = array(
                    5   => 1,
                    6   => 1,
                    10  => 3,
                    12  => 2,
                    13  => 2,
                    14  => 1,
                    15  => 2, // +1 for "excessive @throws" tag.
                    28  => 1,
                    43  => 1,
                    52  => 1,
                    76  => 1,
                    87  => 1,
                    103 => 1,
                    109 => 1,
                    110 => 1,
                    112 => 1,
                    122 => 1,
                    123 => 3,
                    124 => 2,
                    125 => 1,
                    126 => 1,
                    137 => 4,
                    138 => 4,
                    139 => 4,
                    143 => 2,
                    152 => 1,
                    155 => 2,
                    159 => 1,
                    166 => 1,
                    173 => 1,
                    180 => 1,
                    183 => 1,
                    190 => 2,
                    193 => 2,
                    196 => 1,
                    199 => 3, // +1 for "excessive @throws" tag.
                    210 => 1,
                    211 => 1,
                    222 => 1,
                    223 => 1,
                    224 => 1,
                    225 => 1,
                    226 => 1,
                    227 => 1,
                    230 => 2,
                    232 => 2,
                    246 => 1,
                    248 => 4,
                    261 => 1,
                    263 => 1,
                    276 => 1,
                    277 => 1,
                    278 => 1,
                    279 => 1,
                    280 => 1,
                    281 => 1,
                    284 => 1,
                    286 => 7,
                    294 => 1,
                    302 => 1,
                    312 => 1,
                    358 => 1,
                    359 => 2,
                    372 => 1,
                    373 => 1,
                    387 => 1,
                    407 => 1,
                    441 => 1,
                    500 => 1,
                    526 => 1,
                    548 => 1,
                    641 => 1,
                    657 => 1,
                   );

            return $ret;
        } else if ($testFile === 'FunctionCommentUnitTest.2.inc') {
            return array(
                    // Square bracket not allowed as function short description start.
                    7  => 1,

                    // Square bracket is allowed as event short description start.
                    17 => 0,

                    // Incorrect event short description start.
                    27 => 1,
                   );
        } else if ($testFile === 'FunctionCommentUnitTest.3.inc') {
            $ret = array(
                    // Square bracket not allowed as function short description start.
                    7  => 1,

                    // Square bracket is allowed as event short description start.
                    17 => 0,

                    // Incorrect event short description start.
                    27 => 1,
                   );

            return $ret;
        }//end if

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

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
class CodingStandard_Tests_Commenting_FunctionCommentUnitTest extends AbstractSniffUnitTest
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
            return array(
                    6   => 1,
                    8   => 1,
                    10  => 4,
                    12  => 3,
                    13  => 3,
                    14  => 1,
                    15  => 1,
                    28  => 1,
                    35  => 3,
                    38  => 1,
                    40  => 1,
                    41  => 1,
                    43  => 1,
                    52  => 1,
                    53  => 1,
                    103 => 1,
                    109 => 1,
                    110 => 1,
                    112 => 2,
                    122 => 1,
                    123 => 4,
                    124 => 3,
                    125 => 4,
                    126 => 6,
                    137 => 3,
                    138 => 2,
                    139 => 3,
                    143 => 2,
                    155 => 2,
                    158 => 1,
                    166 => 1,
                    173 => 1,
                    180 => 1,
                    183 => 1,
                    193 => 4,
                    195 => 1,
                    196 => 1,
                    199 => 2,
                    210 => 1,
                    211 => 1,
                    222 => 1,
                    223 => 1,
                    224 => 1,
                    225 => 1,
                    226 => 1,
                    227 => 1,
                    230 => 2,
                    232 => 1,
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
                    286 => 2,
                    293 => 1,
                    300 => 1,
                    308 => 1,
                    318 => 1,
                    334 => 1,
                    344 => 1,
                    358 => 1,
                    359 => 1,
                    373 => 2,
                    387 => 1,
                    407 => 1,
                    441 => 1,
                    470 => 1,
                    474 => 1,
                    500 => 1,
                    526 => 1,
                    548 => 1,
                    619 => 1,
                   );
        } else if ($testFile === 'FunctionCommentUnitTest.2.inc') {
            return array(
                    // Square bracket not allowed as function short description start.
                    7  => 1,

                    // Square bracket is allowed as event short description start.
                    17 => 0,

                    // Incorrect event short description start.
                    27 => 1,
                   );
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

?>

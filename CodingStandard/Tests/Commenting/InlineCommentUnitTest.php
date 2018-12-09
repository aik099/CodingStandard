<?php
/**
 * CodingStandard_Tests_Commenting_InlineCommentUnitTest.
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
 * Unit test class for the InlineComment sniff.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings are stored in this class.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class InlineCommentUnitTest extends AbstractSniffUnitTest
{


    /**
     * Returns the lines where errors should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of errors that should occur on that line.
     *
     * @param string $testFile The name of the file being tested.
     *
     * @return array(int => int)
     */
    public function getErrorList($testFile)
    {
        switch ($testFile) {
            case 'InlineCommentUnitTest.inc':
                $errors = array(
                           17  => 1,
                           27  => 1,
                           28  => 1,
                           32  => 2,
                           36  => 1,
                           44  => 2,
                           54  => 1,
                           58  => 1,
                           61  => 1,
                           64  => 1,
                           67  => 1,
                           95  => 1,
                           96  => 1,
                           97  => 2,
                           // The @var inline comments are allowed.
                           118 => 0,
                           130 => 1,
                           133 => 1,
                           156 => 1,
                           // Comments starting with non-letter are allowed.
                           159 => 0,
                           162 => 0,
                          );

                return $errors;

            case 'InlineCommentUnitTest.js':
                return array(
                        31  => 1,
                        36  => 2,
                        44  => 1,
                        48  => 1,
                        51  => 1,
                        54  => 1,
                        57  => 1,
                        102 => 1,
                        103 => 1,
                        104 => 3,
                        // Comments starting with digit are allowed.
                        113 => 0,
                       );
        }//end switch

        return array();

    }//end getErrorList()


    /**
     * Returns the lines where warnings should occur.
     *
     * The key of the array should represent the line number and the value
     * should represent the number of warnings that should occur on that line.
     *
     * @param string $testFile The name of the file being tested.
     *
     * @return array(int => int)
     */
    public function getWarningList($testFile)
    {
        return array();

    }//end getWarningList()


}//end class

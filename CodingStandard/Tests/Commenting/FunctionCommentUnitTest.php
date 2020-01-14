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

use PHP_CodeSniffer\Config;
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
            $errors = array(
                        5   => 1,
                        10  => 3,
                        12  => 2,
                        13  => 2,
                        14  => 1,
                        15  => 1,
                        28  => 1,
                        43  => 1,
                        76  => 1,
                        87  => 1,
                        103 => 1,
                        109 => 1,
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
                        183 => 1,
                        190 => 2,
                        193 => 2,
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
                        669 => 1,
                        688 => 1,
                        744 => 1,
                        748 => 1,
                        767 => 1,
                        789 => 1,
                        792 => 1,
                        794 => 1,
                        797 => 1,
                        801 => 1,
                        828 => 1,
                        840 => 1,
                        852 => 1,
                        864 => 1,
                        886 => 1,
                        888 => 1,
                        890 => 1,
                        978 => 1,
                        997 => 1,
            );

            // Scalar type hints only work from PHP 7 onwards.
            if (PHP_VERSION_ID >= 70000) {
                $errors[17]   = 3;
                $errors[128]  = 1;
                $errors[143]  = 3;
                $errors[161]  = 2;
                $errors[201]  = 1;
                $errors[232]  = 7;
                $errors[363]  = 3;
                $errors[377]  = 1;
                $errors[575]  = 2;
                $errors[627]  = 1;
                $errors[1002] = 1;
            } else {
                $errors[729] = 4;
                $errors[740] = 2;
                $errors[752] = 2;
                $errors[982] = 1;
            }

            // Object type hints only work from PHP 7.2 onwards.
            if (PHP_VERSION_ID >= 70200) {
                $errors[627] = 2;
            } else {
                $errors[992] = 2;
            }

            // === New error types added over existing Squiz sniff ===.

            // Short comment validation for non-In-Portal event methods.
            $errors[6] = 1;
            $errors[52] = 1;
            $errors[110] = 1;
            $errors[180] = 1;
            // Reports "@throws" tag usage without exception being thrown inside a method.
            $errors[15] += 1;
            $errors[199] += 1;
            $errors[669] += 1;

            return $errors;
        } elseif ($testFile === 'FunctionCommentUnitTest.2.inc') {
            return array(
                    // Square bracket not allowed as function short description start.
                    7  => 1,

                    // Square bracket is allowed as event short description start.
                    17 => 0,

                    // Incorrect event short description start.
                    27 => 1,
                   );
        } elseif ($testFile === 'FunctionCommentUnitTest.3.inc') {
            $errors = array(
                    // Square bracket not allowed as function short description start.
                    7  => 1,

                    // Square bracket is allowed as event short description start.
                    17 => 0,

                    // Incorrect event short description start.
                    27 => 1,
                   );

            return $errors;
        } elseif ($testFile === 'FunctionCommentUnitTest.4.inc') {
            $errors = array(
                    // Return type "$this" used instead of static/self.
                    6 => 1,
                   );

            // Scalar type hints only work from PHP 7 onwards.
            if (PHP_VERSION_ID >= 70000) {
                $errors[23] = 1;
                $errors[38] = 1;
            }

            return $errors;
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


    /**
     * Get a list of CLI values to set before the file is tested.
     *
     * @param string                  $filename The name of the file being tested.
     * @param Config $config   The config data for the run.
     *
     * @return void
     */
    public function setCliValues($filename, Config $config)
    {
        // Don't replace PHP version for test fixture from original sniff.
        if ($filename === 'FunctionCommentUnitTest.1.inc') {
            $config->setConfigData('php_version', null, true);
            return;
        }

        // Replace PHP version for test fixture, that are custom to this standard.
        parent::setCliValues($filename, $config);
    }//end setCliValues()
}//end class

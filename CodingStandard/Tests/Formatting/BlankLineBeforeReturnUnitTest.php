<?php
/**
 * CodingStandard_Tests_Formatting_BlankLineBeforeReturnUnitTest.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Tests\Formatting;

use TestSuite\AbstractSniffUnitTest;

/**
 * Unit test class for the BlankLineBeforeReturn sniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class BlankLineBeforeReturnUnitTest extends AbstractSniffUnitTest
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
                // One blank line before "return" when it's only construct in scope.
                10  => 1,
                // Two blank lines before "return" when it's only construct in scope.
                17  => 1,
                // No blank line before "return" when some code exists before it.
                30  => 1,
                // One blank line before "return" when it's only construct in scope.
                49  => 1,
                53  => 1,
                62  => 1,
                // No blank line when "return" follow scope closing brace.
                76  => 1,
                // One blank line before "return" when it's only construct in scope.
                80  => 1,
                // Two blank lines before "return" and previous construct in same scope.
                101 => 1,
                // No blank line when "return" follow scope closing brace.
                112 => 1,
                // One blank line before "return" when it's only construct in scope.
                117 => 1,
                // Too many blank lines before "return" with an inline comment.
                136 => 1,
                // Not preceding inline comment found.
                143 => 1,
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

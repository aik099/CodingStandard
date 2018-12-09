<?php
/**
 * CodingStandard_Tests_Classes_ClassDeclarationUnitTest.
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
 * Unit test class for the ClassDeclaration sniff.
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
class ClassDeclarationUnitTest extends AbstractSniffUnitTest
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
        $ret = array(
                // Extra space between interfaces, that a class implements.
                2   => 1,
                // No space after comma, when extending several classes or implementing several interfaces.
                // Class opening brace not on a new line.
                7   => 3,
                // The "extends" keyword isn't on same line as the class name.
                12  => 1,
                // The "implements" keyword isn't on same line as the class name.
                13  => 1,
                // Extra new line before class closing brace.
                17  => 1,
                // Extra space before extended class name or "implements" keyword.
                19  => 2,
                // Incorrect 1st implemented interface name indentation (when on separate line).
                20  => 1,
                // Incorrect 2nd implemented interface name indentation (when on separate line).
                21  => 1,
                // Class opening brace not on a new line right after definition.
                22  => 1,
                // Expected 1 space before "implements" keyword.
                // The first item in a multi-line implements list must be on the line following the implements keyword.
                27  => 2,
                // Incorrect 2nd implemented interface name indentation (when on separate line).
                34  => 1,
                // Several interfaces implemented on a line in multi-line class declaration.
                35  => 2,
                // Class declaration indented incorrectly.
                42  => 1,
                // Incorrectly indented interface names multi-line class declaration.
                44  => 1,
                // Incorrectly indented interface names multi-line class declaration.
                45  => 1,
                // No empty line after closing class brace. Incorrect class declaration indentation.
                48  => 2,
                // Comma placed not immediately after interface, when class implements several.
                63  => 1,
                // Incorrect extended interface indentation in multi-line interface declaration.
                116 => 1,
                // Incorrect extended interface indentation in multi-line interface declaration.
                118 => 1,
                119 => 1,
                // Expected 1 space between abstract and class keywords; newline found.
                124 => 1,
                130 => 2,
                131 => 1,
                // Too much empty lines after interface declaration.
                134 => 1,
                // Class closing brace must on it's own line and must have an empty line after it.
                142 => 1,
               );

        return $ret;
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

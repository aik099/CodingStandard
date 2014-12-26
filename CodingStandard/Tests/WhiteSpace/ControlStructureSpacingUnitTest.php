<?php
/**
 * CodingStandard_Tests_WhiteSpace_ControlStructureSpacingUnitTest.
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
 * Unit test class for the ControlStructureSpacing sniff.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Tests_WhiteSpace_ControlStructureSpacingUnitTest extends AbstractSniffUnitTest
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
                // IF (condition spacing, blank line at the scope edges).
                77   => 3,
                81   => 1,
                82   => 3,
                86   => 1,
                87   => 3,
                91   => 1,
                92   => 1,
                96   => 1,
                98   => 3,
                102  => 1,
                103  => 3,
                107  => 1,
                108  => 3,
                112  => 1,
                114  => 3,
                118  => 1,
                119  => 3,
                123  => 1,
                124  => 1,
                128  => 1,
                130  => 3,
                134  => 1,
                135  => 1,
                139  => 1,
                141  => 3,
                145  => 1,
                // WHILE (condition spacing, blank line at the scope edges).
                147  => 3,
                151  => 1,
                // DO (condition spacing, blank line at the scope edges).
                153  => 1,
                157  => 3,
                // FOREACH (condition spacing, blank line at the scope edges).
                159  => 3,
                163  => 1,
                165  => 3,
                169  => 1,
                // FOR (condition spacing, blank line at the scope edges).
                171  => 3,
                175  => 1,
                // SWITCH (condition spacing, blank line at the scope edges).
                177  => 3,
                181  => 1,
                // TRY/CATCH (condition spacing, blank line at the scope edges).
                183  => 1,
                187  => 1,
                188  => 3,
                192  => 1,

                // IF (empty control structure).
                194  => 1,
                197  => 1,
                200  => 1,
                203  => 1,
                207  => 1,
                210  => 1,
                213  => 1,
                217  => 1,
                220  => 1,
                223  => 1,
                227  => 1,
                230  => 1,
                234  => 1,
                // WHILE (empty control structure).
                238  => 1,
                // DO (empty control structure).
                242  => 1,
                // FOREACH (empty control structure).
                246  => 1,
                250  => 1,
                // FOR (empty control structure).
                254  => 1,
                // SWITCH (empty control structure).
                258  => 1,
                // TRY/CATCH (empty control structure).
                262  => 1,
                265  => 1,

                // IF (empty line before/after within "case").
                474  => 1,
                485  => 1,
                491  => 1,
                499  => 1,
                505  => 1,
                513  => 1,
                519  => 1,
                524  => 1,
                530  => 1,
                532  => 1,
                // WHILE (empty line before/after within "case").
                538  => 1,
                540  => 1,
                // DO (empty line before/after within "case").
                546  => 1,
                548  => 1,
                // FOREACH (empty line before/after within "case").
                554  => 1,
                556  => 1,
                562  => 1,
                564  => 1,
                // FOR (empty line before/after within "case").
                570  => 1,
                572  => 1,
                // SWITCH (empty line before/after within "case").
                578  => 1,
                580  => 1,
                // TRY/CATCH (empty line before/after within "case").
                586  => 1,
                591  => 1,

                // IF (empty line before/after within "default").
                599  => 1,
                610  => 1,
                616  => 1,
                624  => 1,
                630  => 1,
                638  => 1,
                644  => 1,
                649  => 1,
                655  => 1,
                657  => 1,
                // WHILE (empty line before/after within "default").
                663  => 1,
                665  => 1,
                // DO (empty line before/after within "default").
                671  => 1,
                673  => 1,
                // FOREACH (empty line before/after within "default").
                679  => 1,
                681  => 1,
                687  => 1,
                689  => 1,
                // FOR (empty line before/after within "default").
                695  => 1,
                697  => 1,
                // SWITCH (empty line before/after within "default").
                703  => 1,
                705  => 1,
                // TRY/CATCH (empty line before/after within "default").
                711  => 1,
                716  => 1,

                // IF (no blank line before/after).
                722  => 1,
                733  => 1,
                735  => 1,
                743  => 1,
                745  => 1,
                753  => 1,
                755  => 1,
                760  => 1,
                762  => 1,
                764  => 1,
                // WHILE (no blank line before/after).
                766  => 1,
                768  => 1,
                // DO (no blank line before/after).
                770  => 1,
                772  => 1,
                // FOREACH (no blank line before/after).
                774  => 1,
                776  => 1,
                778  => 1,
                780  => 1,
                // FOR (no blank line before/after).
                782  => 1,
                784  => 1,
                // SWITCH (no blank line before/after).
                786  => 1,
                788  => 1,
                // TRY/CATCH (no blank line before/after).
                790  => 1,
                795  => 1,

                // IF (no blank line before/after inline comment).
                1035  => 1,
                1046  => 1,
                1050  => 1,
                1058  => 1,
                1062  => 1,
                1070  => 1,
                1074  => 1,
                1079  => 1,
                1083  => 1,
                1085  => 1,
                // WHILE (no blank line before/after inline comment).
                1089  => 1,
                1091  => 1,
                // DO (no blank line before/after inline comment).
                1095 => 1,
                1097 => 1,
                // FOREACH (no blank line before/after inline comment).
                1101 => 1,
                1103 => 1,
                1107 => 1,
                1109 => 1,
                // FOR (no blank line before/after inline comment).
                1113 => 1,
                1115 => 1,
                // SWITCH (no blank line before/after inline comment).
                1119 => 1,
                1121 => 1,
                // TRY/CATCH (no blank line before/after inline comment).
                1125 => 1,
                1130 => 1,
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

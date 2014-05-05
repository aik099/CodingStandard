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
                70   => 3,
                74   => 1,
                75   => 3,
                79   => 1,
                80   => 3,
                84   => 1,
                85   => 1,
                89   => 1,
                91   => 3,
                95   => 1,
                96   => 3,
                100  => 1,
                101  => 3,
                105  => 1,
                107  => 3,
                111  => 1,
                112  => 3,
                116  => 1,
                117  => 1,
                121  => 1,
                123  => 3,
                127  => 1,
                128  => 1,
                132  => 1,
                134  => 3,
                138  => 1,
                // WHILE (condition spacing, blank line at the scope edges).
                140  => 3,
                144  => 1,
                // DO (condition spacing, blank line at the scope edges).
                146  => 1,
                150  => 3,
                // FOREACH (condition spacing, blank line at the scope edges).
                152  => 3,
                156  => 1,
                158  => 3,
                162  => 1,
                // FOR (condition spacing, blank line at the scope edges).
                164  => 3,
                168  => 1,
                // SWITCH (condition spacing, blank line at the scope edges).
                170  => 3,
                174  => 1,

                // IF (empty control structure).
                176  => 1,
                179  => 1,
                182  => 1,
                185  => 1,
                189  => 1,
                192  => 1,
                195  => 1,
                199  => 1,
                202  => 1,
                205  => 1,
                209  => 1,
                212  => 1,
                216  => 1,
                // WHILE (empty control structure).
                220  => 1,
                // DO (empty control structure).
                224  => 1,
                // FOREACH (empty control structure).
                228  => 1,
                232  => 1,
                // FOR (empty control structure).
                236  => 1,
                // SWITCH (empty control structure).
                240  => 1,

                // IF (empty line before/after within "case").
                431  => 1,
                442  => 1,
                448  => 1,
                456  => 1,
                462  => 1,
                470  => 1,
                476  => 1,
                481  => 1,
                487  => 1,
                489  => 1,
                // WHILE (empty line before/after within "case").
                495  => 1,
                497  => 1,
                // DO (empty line before/after within "case").
                503  => 1,
                505  => 1,
                // FOREACH (empty line before/after within "case").
                511  => 1,
                513  => 1,
                519  => 1,
                521  => 1,
                // FOR (empty line before/after within "case").
                527  => 1,
                529  => 1,
                // SWITCH (empty line before/after within "case").
                535  => 1,
                537  => 1,

                // IF (empty line before/after within "default").
                545  => 1,
                556  => 1,
                562  => 1,
                570  => 1,
                576  => 1,
                584  => 1,
                590  => 1,
                595  => 1,
                601  => 1,
                603  => 1,
                // WHILE (empty line before/after within "default").
                609  => 1,
                611  => 1,
                // DO (empty line before/after within "default").
                617  => 1,
                619  => 1,
                // FOREACH (empty line before/after within "default").
                625  => 1,
                627  => 1,
                633  => 1,
                635  => 1,
                // FOR (empty line before/after within "default").
                641  => 1,
                643  => 1,
                // SWITCH (empty line before/after within "default").
                649  => 1,
                651  => 1,

                // IF (no blank line before/after).
                657  => 1,
                668  => 1,
                670  => 1,
                678  => 1,
                680  => 1,
                688  => 1,
                690  => 1,
                695  => 1,
                697  => 1,
                699  => 1,
                // WHILE (no blank line before/after).
                701  => 1,
                703  => 1,
                // DO (no blank line before/after).
                705  => 1,
                707  => 1,
                // FOREACH (no blank line before/after).
                709  => 1,
                711  => 1,
                713  => 1,
                715  => 1,
                // FOR (no blank line before/after).
                717  => 1,
                719  => 1,
                // SWITCH (no blank line before/after).
                721  => 1,
                723  => 1,

                // IF (no blank line before/after inline comment).
                942  => 1,
                953  => 1,
                957  => 1,
                965  => 1,
                969  => 1,
                977  => 1,
                981  => 1,
                986  => 1,
                990  => 1,
                992  => 1,
                // WHILE (no blank line before/after inline comment).
                996  => 1,
                998  => 1,
                // DO (no blank line before/after inline comment).
                1002 => 1,
                1004 => 1,
                // FOREACH (no blank line before/after inline comment).
                1008 => 1,
                1010 => 1,
                1014 => 1,
                1016 => 1,
                // FOR (no blank line before/after inline comment).
                1020 => 1,
                1022 => 1,
                // SWITCH (no blank line before/after inline comment).
                1026 => 1,
                1028 => 1,
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

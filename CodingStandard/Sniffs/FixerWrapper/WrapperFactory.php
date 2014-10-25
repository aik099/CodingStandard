<?php
/**
 * CodingStandard_Sniffs_FixerWrapper_WrapperFactory.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

/**
 * CodingStandard_Sniffs_FixerWrapper_WrapperFactory.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_FixerWrapper_WrapperFactory
{


    /**
     * Creates fixer instance based on given file.
     *
     * @param PHP_CodeSniffer_File $phpcsFile File to scan.
     *
     * @return CodingStandard_Sniffs_FixerWrapper_AbstractWrapper
     */
    public static function createWrapper(PHP_CodeSniffer_File $phpcsFile)
    {
        if (isset($phpcsFile->fixer) === true) {
            return new CodingStandard_Sniffs_FixerWrapper_RealWrapper($phpcsFile);
        }

        return new CodingStandard_Sniffs_FixerWrapper_DummyWrapper($phpcsFile);

    }//end createWrapper()


}//end class

?>

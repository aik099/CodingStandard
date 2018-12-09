<?php
/**
 * CodingStandard_Sniffs_NamingConventions_ValidInterfaceNameSniff.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Symfony2-phpcs-authors <Symfony2-coding-standard@opensky.github.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * CodingStandard_Sniffs_NamingConventions_ValidInterfaceNameSniff.
 *
 * Throws errors if interface names are not prefixed with "I".
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class ValidInterfaceNameSniff implements Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array('PHP');


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_INTERFACE);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the
     *                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $interfaceName = $phpcsFile->getDeclarationName($stackPtr);
        $firstLetter   = $interfaceName[0];
        $secondLetter  = $interfaceName[1];

        if ($firstLetter !== 'I' || $secondLetter === strtolower($secondLetter)) {
            $phpcsFile->addError(
                'Interface name is not prefixed with "I"',
                $stackPtr,
                'WrongPrefix'
            );
        }

    }//end process()


}//end class

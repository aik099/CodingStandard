<?php
/**
 * CodingStandard_Sniffs_NamingConventions_ValidTraitNameSniff.
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

/**
 * CodingStandard_Sniffs_NamingConventions_ValidTraitNameSniff.
 *
 * Throws errors if trait names are not prefixed with "T".
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_NamingConventions_ValidTraitNameSniff implements PHP_CodeSniffer_Sniff
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
     * @return array
     */
    public function register()
    {
        return array(T_TRAIT);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $interfaceName = $phpcsFile->getDeclarationName($stackPtr);
        $firstLetter   = $interfaceName[0];
        $secondLetter  = $interfaceName[1];

        if ($firstLetter !== 'T' || $secondLetter === strtolower($secondLetter)) {
            $phpcsFile->addError(
                'Trait name is not prefixed with "T"',
                $stackPtr
            );
        }

    }//end process()


}//end class

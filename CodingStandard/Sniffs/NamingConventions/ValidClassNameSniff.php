<?php
/**
 * CodingStandard_Sniffs_NamingConventions_ValidClassNameSniff.
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
 * CodingStandard_Sniffs_NamingConventions_ValidClassNameSniff.
 *
 * Throws errors if abstract class names are not prefixed with "Abstract".
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_NamingConventions_ValidClassNameSniff implements PHP_CodeSniffer_Sniff
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
        return array(T_CLASS);

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
        $className = $phpcsFile->getDeclarationName($stackPtr);

        if (!$className) {
            return;
        }

        $classProperties = $phpcsFile->getClassProperties($stackPtr);
        $hasAbstractName = substr($className, 0, 8) === 'Abstract';

        if ($classProperties['is_abstract'] === true && $hasAbstractName === false) {
            $phpcsFile->addError(
                'Abstract class name "%s" is not prefixed with "Abstract"',
                $stackPtr,
                'AbstractWrongName',
                array($className)
            );
        } elseif ($classProperties['is_abstract'] === false && $hasAbstractName === true) {
            $phpcsFile->addError(
                'Non-abstract class name "%s" is prefixed with "Abstract"',
                $stackPtr,
                'AbstractMissingModifier',
                array($className)
            );
        }

    }//end process()


}//end class

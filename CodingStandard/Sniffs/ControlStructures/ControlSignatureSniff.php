<?php
/**
 * CodingStandard_Sniffs_ControlStructures_ControlSignatureSniff.
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

// @codeCoverageIgnoreStart
if (class_exists('PHP_CodeSniffer_Standards_AbstractPatternSniff', true) === false) {
    $error = 'Class PHP_CodeSniffer_Standards_AbstractPatternSniff not found';
    throw new PHP_CodeSniffer_Exception($error);
}
// @codeCoverageIgnoreEnd

/**
 * Verifies that control statements conform to their coding standards.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Greg Sherwood <gsherwood@squiz.net>
 * @author   Marc McIntyre <mmcintyre@squiz.net>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_ControlStructures_ControlSignatureSniff extends
 PHP_CodeSniffer_Standards_AbstractPatternSniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );


    /**
     * Constructs a PEAR_Sniffs_ControlStructures_ControlSignatureSniff.
     */
    public function __construct()
    {
        parent::__construct(true);

    }//end __construct()


    /**
     * Returns the patterns that this test wishes to verify.
     *
     * @return string[]
     */
    protected function getPatterns()
    {
        return array(
                'do {EOL...} while (...);EOL',
                'while (...) {EOL',
                'switch (...) {EOL',
                'for (...) {EOL',
                'if (...) {EOL',
                'foreach (...) {EOL',
                '}EOLelseif (...) {EOL',
                '}EOLelse {EOL',
                'do {EOL',
                'try {EOL',
                '}EOLcatch (...) {EOL'
               );

    }//end getPatterns()


}//end class

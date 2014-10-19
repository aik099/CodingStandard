<?php
/**
 * Ensures doc blocks follow basic formatting.
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
 * Ensures doc blocks follow basic formatting.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

class CodingStandard_Sniffs_Commenting_DocCommentWrapperSniff implements PHP_CodeSniffer_Sniff
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
     * Instance of DocCommentSniff
     *
     * @var Generic_Sniffs_Commenting_DocCommentSniff
     */
    private $_docCommentSniff;


    /**
     * Creates sniff.
     */
    public function __construct()
    {
        if (class_exists('Generic_Sniffs_Commenting_DocCommentSniff') === true) {
            $this->_docCommentSniff = new Generic_Sniffs_Commenting_DocCommentSniff();
        }

    }//end __construct()


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        if (isset($this->_docCommentSniff) === true) {
            return $this->_docCommentSniff->register();
        }

        return array();

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $commentEnd   = $phpcsFile->findNext(T_DOC_COMMENT_CLOSE_TAG, ($stackPtr + 1));
        $commentStart = $tokens[$commentEnd]['comment_opener'];

        if ($tokens[$commentStart]['line'] === $tokens[$commentEnd]['line']) {
            $commentText = $phpcsFile->getTokensAsString($commentStart, ($commentEnd - $commentStart + 1));

            if (strpos($commentText, '@var') !== false || strpos($commentText, '@type') !== false) {
                // Skip inline block comments with variable type definition.
                return;
            }
        }

        $this->_docCommentSniff->process($phpcsFile, $stackPtr);

    }//end process()


}//end class

?>

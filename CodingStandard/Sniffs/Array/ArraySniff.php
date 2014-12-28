<?php
/**
 * CodingStandard_Sniffs_Array_ArraySniff.
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
 * CodingStandard_Sniffs_Array_ArraySniff.
 *
 * Checks if the array's are styled in the Drupal way.
 * - Comma after the last array element
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Peter Philipp <peter.philipp@cando-image.com>
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */
class CodingStandard_Sniffs_Array_ArraySniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return integer[]
     */
    public function register()
    {
        return array(T_ARRAY);

    }//end register()


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens     = $phpcsFile->getTokens();
        $arrayStart = $tokens[$stackPtr]['parenthesis_opener'];
        $arrayEnd   = $tokens[$arrayStart]['parenthesis_closer'];

        if ($arrayStart !== ($stackPtr + 1)) {
            $error = 'There must be no space between the Array keyword and the opening parenthesis';
            $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterKeyword');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();

                for ($i = ($stackPtr + 1); $i < $arrayStart; $i++) {
                    $phpcsFile->fixer->replaceToken($i, '');
                }

                $phpcsFile->fixer->endChangeset();
            }
        }

        // Check for empty arrays.
        $content = $phpcsFile->findNext(array(T_WHITESPACE), ($arrayStart + 1), ($arrayEnd + 1), true);
        if ($content === $arrayEnd) {
            // Empty array, but if the brackets aren't together, there's a problem.
            if (($arrayEnd - $arrayStart) !== 1) {
                $error = 'Empty array declaration must have no space between the parentheses';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceInEmptyArray');
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($arrayStart + 1); $i < $arrayEnd; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }

                // We can return here because there is nothing else to check. All code
                // below can assume that the array is not empty.
                return;
            }
        }

        $lastItem = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($arrayEnd - 1), $stackPtr, true);

        // Empty array.
        if ($lastItem === $arrayStart) {
            return;
        }

        // Inline array.
        $isInlineArray = $tokens[$arrayStart]['line'] === $tokens[$arrayEnd]['line'];

        // Check if the last item in a multiline array has a "closing" comma.
        if ($tokens[$lastItem]['code'] !== T_COMMA && $isInlineArray === false) {
            $error = 'A comma should follow the last multiline array item. Found: '.$tokens[$lastItem]['content'];
            $fix   = $phpcsFile->addFixableWarning($error, $lastItem, 'NoLastComma');
            if ($fix === true) {
                $phpcsFile->fixer->beginChangeset();
                $phpcsFile->fixer->addContent($lastItem, ',');
                $phpcsFile->fixer->endChangeset();
            }

            return;
        }

        if ($isInlineArray === true) {
            if ($tokens[$lastItem]['code'] === T_COMMA) {
                $error = 'Comma not allowed after last value in single-line array declaration';
                $fix   = $phpcsFile->addFixableWarning($error, $lastItem, 'LastComma');
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();
                    $phpcsFile->fixer->replaceToken($lastItem, '');
                    $phpcsFile->fixer->endChangeset();
                }

                return;
            }

            // Inline array must not have spaces within parenthesis.
            if ($content !== ($arrayStart + 1)) {
                $error = 'Space found after opening parenthesis of Array';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceAfterOpen');
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($arrayStart + 1); $i < $content; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            }

            if ($lastItem !== ($arrayEnd - 1)) {
                $error = 'Space found before closing parenthesis of Array';
                $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'SpaceBeforeClose');
                if ($fix === true) {
                    $phpcsFile->fixer->beginChangeset();

                    for ($i = ($lastItem + 1); $i < $arrayEnd; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }

                    $phpcsFile->fixer->endChangeset();
                }
            }
        }//end if

    }//end process()


}//end class

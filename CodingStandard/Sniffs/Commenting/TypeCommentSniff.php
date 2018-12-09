<?php
/**
 * Ensures type comments follow basic formatting.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

namespace CodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensures type comments follow basic formatting.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Alexander Obuhovich <aik.bold@gmail.com>
 * @license  https://github.com/aik099/CodingStandard/blob/master/LICENSE BSD 3-Clause
 * @link     https://github.com/aik099/CodingStandard
 */

class TypeCommentSniff implements Sniff
{

    const TYPE_TAG = '@var';

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
        return array(
                T_COMMENT,
                T_DOC_COMMENT_OPEN_TAG,
               );

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
        $tokens = $phpcsFile->getTokens();

        if ($tokens[$stackPtr]['code'] === T_COMMENT) {
            $this->processComment($phpcsFile, $stackPtr);
        } else if ($tokens[$stackPtr]['code'] === T_DOC_COMMENT_OPEN_TAG) {
            $this->processDocBlock($phpcsFile, $stackPtr);
        }

    }//end process()


    /**
     * Processes comment.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processComment(File $phpcsFile, $stackPtr)
    {
        $tokens      = $phpcsFile->getTokens();
        $commentText = $tokens[$stackPtr]['content'];

        // Multi-line block comment.
        if (substr($commentText, 0, 2) !== '/*' || substr($commentText, -2) !== '*/') {
            return;
        }

        // Not a type comment.
        if ($this->isTypeComment($commentText) === false) {
            return;
        }

        // The "/**@var ...*/" comment isn't parsed as DocBlock and is fixed here.
        $error = 'Type comment must be in "/** %s ClassName $variable_name */" format';
        $fix   = $phpcsFile->addFixableError($error, $stackPtr, 'WrongStyle', array(self::TYPE_TAG));
        if ($fix === true) {
            $phpcsFile->fixer->replaceToken($stackPtr, '/** '.trim($commentText, ' /*').' */');
        }

    }//end processComment()


    /**
     * Processes DocBlock.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function processDocBlock(File $phpcsFile, $stackPtr)
    {
        $tokens       = $phpcsFile->getTokens();
        $commentStart = $stackPtr;
        $commentEnd   = $tokens[$stackPtr]['comment_closer'];

        // Multi-line DocBlock.
        if ($tokens[$commentEnd]['line'] !== $tokens[$commentStart]['line']) {
            return;
        }

        $commentTags = $tokens[$stackPtr]['comment_tags'];

        // Single-line DocBlock without tags inside.
        if (empty($commentTags) === true) {
            return;
        }

        // First tag will always exist.
        $firstTagPtr = $commentTags[0];

        // Not a type comment.
        if ($this->isTypeComment($tokens[$firstTagPtr]['content']) === false) {
            return;
        }

        $structure = new TypeCommentStructure($phpcsFile, $stackPtr);

        if ($structure->className === null) {
            $error              = 'Type comment must be in "/** %s ClassName $variable_name */" format';
            $leadingWhitespace  = $tokens[$stackPtr + 1]['code'] === T_DOC_COMMENT_WHITESPACE;
            $trailingWhitespace = $tokens[$commentEnd - 1]['code'] === T_DOC_COMMENT_WHITESPACE;

            if ($leadingWhitespace === false || $trailingWhitespace === false) {
                $fix = $phpcsFile->addFixableError($error, $stackPtr, 'WrongStyle', array(self::TYPE_TAG));
                if ($fix === true) {
                    if ($leadingWhitespace === false) {
                        $phpcsFile->fixer->addContentBefore($stackPtr + 1, ' ');
                    }

                    if ($trailingWhitespace === false) {
                        $phpcsFile->fixer->addContent($commentEnd - 1, ' ');
                    }
                }
            } else {
                $phpcsFile->addError($error, $firstTagPtr, 'WrongStyle', array(self::TYPE_TAG));
            }

            return;
        }//end if

        $this->processDocBlockContent($phpcsFile, $stackPtr, $structure);
        $this->processVariableAssociation($phpcsFile, $stackPtr, $structure);

    }//end processDocBlock()


    /**
     * Processes DocBlock content.
     *
     * @param File                 $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param TypeCommentStructure $structure Type comment structure.
     *
     * @return void
     */
    public function processDocBlockContent(
        File $phpcsFile,
        $stackPtr,
        TypeCommentStructure $structure
    ) {
        $tokens      = $phpcsFile->getTokens();
        $commentTags = $tokens[$stackPtr]['comment_tags'];
        $firstTagPtr = $commentTags[0];

        // Check correct tag usage.
        if ($tokens[$firstTagPtr]['content'] !== self::TYPE_TAG) {
            $fix = $phpcsFile->addFixableError(
                'Type comment must use "%s" tag; "%s" used',
                $firstTagPtr,
                'WrongTag',
                array(
                 self::TYPE_TAG,
                 $tokens[$firstTagPtr]['content'],
                )
            );
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($firstTagPtr, self::TYPE_TAG);
            }
        }

        // Check spacing around the tag.
        $spaceBeforeTagPtr = ($stackPtr + 1);
        if ($tokens[$spaceBeforeTagPtr]['length'] !== 1) {
            $fix = $phpcsFile->addFixableError(
                'There must be 1 space before "%s" tag; %d found',
                $firstTagPtr,
                'SpaceBeforeTag',
                array(
                 $tokens[$firstTagPtr]['content'],
                 $tokens[$spaceBeforeTagPtr]['length'],
                )
            );
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($spaceBeforeTagPtr, ' ');
            }
        }

        $spaceAfterTagPtr = ($stackPtr + 3);
        if ($tokens[$spaceAfterTagPtr]['length'] !== 1) {
            $fix = $phpcsFile->addFixableError(
                'There must be 1 space between "%s" tag and class name; %d found',
                $firstTagPtr,
                'SpaceAfterTag',
                array(
                 $tokens[$firstTagPtr]['content'],
                 $tokens[$spaceAfterTagPtr]['length'],
                )
            );
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($spaceAfterTagPtr, ' ');
            }
        }

        // Check presence of both type & variable.
        if ($structure->variableName === null) {
            if ($structure->isVariable($structure->className) === false) {
                $phpcsFile->addError(
                    'Type comment missing variable: /** %s %s ______ */',
                    $structure->tagContentPtr,
                    'VariableMissing',
                    array(
                     self::TYPE_TAG,
                     $structure->className,
                    )
                );
            } else {
                $phpcsFile->addError(
                    'Type comment missing type: /** %s ______ %s */',
                    $structure->tagContentPtr,
                    'TypeMissing',
                    array(
                     self::TYPE_TAG,
                     $structure->className,
                    )
                );
            }//end if

            return;
        }//end if

        $classPositionCorrect    = $structure->isVariable($structure->className) === false;
        $variablePositionCorrect = $structure->isVariable($structure->variableName);

        // Malformed type definition.
        if (($classPositionCorrect === false && $variablePositionCorrect === true)
            || ($classPositionCorrect === true && $variablePositionCorrect === false)
        ) {
            $error = 'Type comment must be in "/** %s ClassName $variable_name */" format';
            $phpcsFile->addError($error, $firstTagPtr, 'WrongStyle', array(self::TYPE_TAG));

            return;
        }

        if ($classPositionCorrect === true || $variablePositionCorrect === true) {
            $expectedTagContent = $structure->className.' '.$structure->variableName.' '.$structure->description;
        } else {
            $expectedTagContent = $structure->variableName.' '.$structure->className.' '.$structure->description;
        }

        if ($structure->tagContent !== $expectedTagContent) {
            $fix = $phpcsFile->addFixableError(
                'Wrong type and variable spacing/order. Expected: "%s"',
                $structure->tagContentPtr,
                'WrongOrder',
                array($expectedTagContent)
            );
            if ($fix === true) {
                $phpcsFile->fixer->replaceToken($structure->tagContentPtr, $expectedTagContent);
            }
        }

    }//end processDocBlockContent()


    /**
     * Processes variable around DocBlock.
     *
     * @param File                 $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param TypeCommentStructure $structure Type comment structure.
     *
     * @return void
     */
    public function processVariableAssociation(
        File $phpcsFile,
        $stackPtr,
        TypeCommentStructure $structure
    ) {
        // Variable association can't be determined.
        if ($structure->variableName === null || $structure->isVariable($structure->variableName) === false) {
            return;
        }

        $this->processVariableBeforeDocBlock($phpcsFile, $stackPtr, $structure);
        $this->processVariableAfterDocBlock($phpcsFile, $stackPtr, $structure);

    }//end processVariableAssociation()


    /**
     * Processes variable before DocBlock.
     *
     * @param File                 $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param TypeCommentStructure $structure Type comment structure.
     *
     * @return void
     */
    public function processVariableBeforeDocBlock(
        File $phpcsFile,
        $stackPtr,
        TypeCommentStructure $structure
    ) {
        $tokens = $phpcsFile->getTokens();

        $prevStatementEnd = $phpcsFile->findPrevious(
            T_WHITESPACE,
            ($stackPtr - 1),
            null,
            true
        );

        if ($prevStatementEnd === false
            || $tokens[$prevStatementEnd]['code'] !== T_SEMICOLON
        ) {
            return;
        }

        $assignmentTokenPtr = $phpcsFile->findPrevious(
            T_EQUAL,
            ($prevStatementEnd - 1),
            null,
            false,
            null,
            true
        );

        // Not an assignment.
        if ($assignmentTokenPtr === false) {
            return;
        }

        $variableTokenPtr = $phpcsFile->findPrevious(
            T_WHITESPACE,
            ($assignmentTokenPtr - 1),
            null,
            true
        );

        // Assignment not to a variable, mentioned in type comment.
        if ($variableTokenPtr === false
            || $tokens[$variableTokenPtr]['code'] !== T_VARIABLE
            || $tokens[$variableTokenPtr]['content'] !== $structure->variableName
        ) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'Type comment must be placed before variable declaration',
            $stackPtr,
            'AfterVariable'
        );
        if ($fix === true) {
            $move_content = '';
            $copyStartPtr = $this->findFirstOnLine($phpcsFile, $stackPtr);
            $copyEndPtr   = $this->findLastOnLine($phpcsFile, $stackPtr);

            $phpcsFile->fixer->beginChangeset();

            for ($i = $copyStartPtr; $i <= $copyEndPtr; $i++) {
                $move_content .= $phpcsFile->fixer->getTokenContent($i);
                $phpcsFile->fixer->replaceToken($i, '');
            }

            $phpcsFile->fixer->addContentBefore(
                $this->findFirstOnLine($phpcsFile, $variableTokenPtr),
                $move_content
            );
            $phpcsFile->fixer->endChangeset();
        }//end if

    }//end processVariableBeforeDocBlock()


    /**
     * Processes variable before DocBlock.
     *
     * @param File                 $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     * @param TypeCommentStructure $structure Type comment structure.
     *
     * @return void
     */
    public function processVariableAfterDocBlock(
        File $phpcsFile,
        $stackPtr,
        TypeCommentStructure $structure
    ) {
        $tokens           = $phpcsFile->getTokens();
        $variableTokenPtr = $phpcsFile->findNext(
            T_WHITESPACE,
            ($tokens[$stackPtr]['comment_closer'] + 1),
            null,
            true
        );

        // No variable placed on next line after type comment.
        if ($variableTokenPtr === false
            || $tokens[$variableTokenPtr]['code'] !== T_VARIABLE
            || $tokens[$variableTokenPtr]['line'] !== ($tokens[$stackPtr]['line'] + 1)
        ) {
            return;
        }

        if ($tokens[$variableTokenPtr]['content'] !== $structure->variableName) {
            $phpcsFile->addError(
                'Type comment variable mismatch, expected "%s"; found: "%s"',
                $structure->tagContentPtr,
                'VariableMismatch',
                array(
                 $tokens[$variableTokenPtr]['content'],
                 $structure->variableName,
                )
            );

            // Don't apply more checks, unless type comment is in sync with variable.
            return;
        }

        $prevStatementEnd = $phpcsFile->findPrevious(
            T_WHITESPACE,
            ($stackPtr - 1),
            null,
            true
        );

        // Previous statement is absent or placed correctly.
        if ($prevStatementEnd === false
            || $tokens[$prevStatementEnd]['code'] !== T_SEMICOLON
            || $tokens[$prevStatementEnd]['line'] < ($tokens[$stackPtr]['line'] - 1)
        ) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'There must be at least 1 empty line above type comment',
            $stackPtr,
            'EmptyLineAbove'
        );
        if ($fix === true) {
            $phpcsFile->fixer->addNewline($prevStatementEnd);
        }

    }//end processVariableAfterDocBlock()


    /**
     * Checks if comment is type comment.
     *
     * @param string $commentText Comment text.
     *
     * @return bool
     */
    protected function isTypeComment($commentText)
    {
        return strpos($commentText, self::TYPE_TAG) !== false || strpos($commentText, '@type') !== false;

    }//end isTypeComment()


    /**
     * Finds first token on a line.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $start     Start from token.
     *
     * @return int | bool
     */
    public function findFirstOnLine(File $phpcsFile, $start)
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $start; $i >= 0; $i--) {
            if ($tokens[$i]['line'] === $tokens[$start]['line']) {
                continue;
            }

            return ($i + 1);
        }

        return false;

    }//end findFirstOnLine()


    /**
     * Finds last token on a line.
     *
     * @param File $phpcsFile All the tokens found in the document.
     * @param int  $start     Start from token.
     *
     * @return int | bool
     */
    public function findLastOnLine(File $phpcsFile, $start)
    {
        $tokens = $phpcsFile->getTokens();

        for ($i = $start; $i <= $phpcsFile->numTokens; $i++) {
            if ($tokens[$i]['line'] === $tokens[$start]['line']) {
                continue;
            }

            return ($i - 1);
        }

        return false;

    }//end findLastOnLine()


}//end class


/**
 * Type comment structure
 */
class TypeCommentStructure
{

    /**
     * Tag content pointer.
     *
     * @var int
     */
    public $tagContentPtr;

    /**
     * Tag content.
     *
     * @var string
     */
    public $tagContent;

    /**
     * Class name.
     *
     * @var string
     */
    public $className;

    /**
     * Variable name.
     *
     * @var string
     */
    public $variableName;

    /**
     * Description.
     *
     * @var string
     */
    public $description;

    /**
     * Token sequence.
     *
     * @var array
     */
    protected $tokenSequence = array(
                                1 => T_DOC_COMMENT_WHITESPACE,
                                2 => T_DOC_COMMENT_TAG,
                                3 => T_DOC_COMMENT_WHITESPACE,
                                4 => T_DOC_COMMENT_STRING,
                               );


    /**
     * Creates from tokens.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token
     *                        in the stack passed in $tokens.
     */
    public function __construct(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Ensure, that DocBlock is built from correct tokens.
        for ($i = 1; $i <= 4; $i++) {
            if ($tokens[($stackPtr + $i)]['code'] !== $this->tokenSequence[$i]) {
                return;
            }
        }

        $this->tagContentPtr = ($stackPtr + 4);
        $this->tagContent    = $tokens[$this->tagContentPtr]['content'];
        $tagContentParts     = array_values(array_filter(explode(' ', $this->tagContent)));

        if (isset($tagContentParts[0]) === true) {
            $this->className = $tagContentParts[0];
        }

        if (isset($tagContentParts[1]) === true) {
            $this->variableName = $tagContentParts[1];
        }

        if (count($tagContentParts) > 2) {
            $this->description = implode(' ', array_slice($tagContentParts, 2)).' ';
        } else {
            $this->description = '';
        }

    }//end __construct()


    /**
     * Detects if given text is a variable.
     *
     * @param string $text Text.
     *
     * @return bool
     */
    public function isVariable($text)
    {
        return substr($text, 0, 1) === '$';

    }//end isVariable()


}//end class

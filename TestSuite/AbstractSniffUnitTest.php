<?php
/**
 * An abstract class that all sniff unit tests must extend.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * An abstract class that all sniff unit tests must extend.
 *
 * A sniff unit test checks a .inc file for expected violations of a single
 * coding standard. Expected errors and warnings that are not found, or
 * warnings and errors that are not expected, are considered test failures.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2012 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 * @version   Release: @package_version@
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
abstract class AbstractSniffUnitTest extends PHPUnit_Framework_TestCase
{

    /**
     * The PHP_CodeSniffer object used for testing.
     *
     * @var PHP_CodeSniffer
     */
    protected static $phpcs = null;


    /**
     * Sets up this unit test.
     *
     * @return void
     */
    protected function setUp()
    {
        if (self::$phpcs === null) {
            self::$phpcs = new PHP_CodeSniffer();

            // Conflicts with Composer AutoLoader.
            spl_autoload_unregister(array('PHP_CodeSniffer', 'autoload'));
        }

    }//end setUp()


    /**
     * Tests the extending classes Sniff class.
     *
     * @return void
     */
    public final function testStandard()
    {
        // Skip this test if we can't run in this environment.
        if ($this->shouldSkipTest() === true) {
            $this->markTestSkipped();
        }

        if (method_exists(self::$phpcs, 'initStandard') === true) {
            // Only init standard without processing it's files, when possible.
            self::$phpcs->initStandard($this->getStandardName(), array($this->getSniffCode()));
        } else {
            // Init standard and process all it's files.
            self::$phpcs->process(array(), $this->getStandardName(), array($this->getSniffCode()));
        }

        self::$phpcs->setIgnorePatterns(array());

        $failureMessages = array();
        $fixingSupported = $this->isFixingSupported();
        foreach ($this->_getTestFiles() as $testFile) {
            try {
                $phpcsFile       = self::$phpcs->processFile($testFile);
                $failureMessages = array_merge($failureMessages, $this->generateFailureMessages($phpcsFile));

                if ($fixingSupported === true && $phpcsFile->getFixableCount() > 0) {
                    // Attempt to fix the errors.
                    $phpcsFile->fixer->fixFile();
                    $fixable = $phpcsFile->getFixableCount();
                    if ($fixable > 0) {
                        $filename = basename($testFile);
                        $failureMessages[] = "Failed to fix $fixable fixable violations in $filename.";
                    } else if (file_exists($testFile.'.diff') === true) {
                        $actualDiff   = $phpcsFile->fixer->generateDiff();
                        $expectedDiff = file_get_contents($testFile.'.diff');

                        if (strcmp($actualDiff, $expectedDiff) !== 0) {
                            $filename = basename($testFile);
                            $failureMessages[] = "Fixed version of $filename file does not match expected one.";
                        }
                    }
                }
            } catch (Exception $e) {
                $this->fail('An unexpected exception has been caught: '.$e->getMessage());
            }//end try
        }//end foreach

        if (empty($failureMessages) === false) {
            $this->fail(implode(PHP_EOL, $failureMessages));
        }

    }//end testStandard()


    /**
     * Determines if used PHPCS version supports fixing.
     *
     * @return bool
     */
    protected function isFixingSupported()
    {
        $classReflection = new \ReflectionClass('PHP_CodeSniffer_File');

        return $classReflection->hasProperty('fixer');

    }//end isFixingSupported()


    /**
     * Should this test be skipped for some reason.
     *
     * @return bool
     */
    protected function shouldSkipTest()
    {
        return false;

    }//end shouldSkipTest()


    /**
     * The name of the coding standard we are testing.
     *
     * @return string
     */
    protected function getStandardName()
    {
        $basename = $this->getBaseName();

        return STANDARDS_PATH.DIRECTORY_SEPARATOR.substr($basename, 0, strpos($basename, '_'));

    }//end getStandardName()


    /**
     * The code of the sniff we are testing.
     *
     * @return string
     */
    protected function getSniffCode()
    {
        $parts = explode('_', $this->getBaseName());

        return $parts[0].'.'.$parts[2].'.'.$parts[3];

    }//end getSniffCode()


    /**
     * Get a list of all test files to check. These will have the same base name
     * but different extensions. We ignore the .php file as it is the class.
     *
     * @return array
     */
    private function _getTestFiles()
    {
        // The basis for determining file locations.
        $basename = $this->getBaseName();

        // The name of the dummy file we are testing.
        $testFileBase = STANDARDS_PATH.DIRECTORY_SEPARATOR.str_replace('_', DIRECTORY_SEPARATOR, $basename).'UnitTest.';

        $testFiles = array();

        /** @var SplFileInfo $file */
        $directoryIterator = new DirectoryIterator(dirname($testFileBase));

        foreach ($directoryIterator as $file) {
            $path = $file->getPathname();

            if (substr($path, 0, strlen($testFileBase)) === $testFileBase && $path !== $testFileBase.'php' && $file->getExtension() !== 'diff') {
                $testFiles[] = $path;
            }
        }

        // Get them in order.
        sort($testFiles);

        return $testFiles;

    }//end _getTestFiles()


    /**
     * The basis for determining file locations.
     *
     * @return string
     */
    protected function getBaseName()
    {
        return substr(get_class($this), 0, -8);

    }//end getBaseName()


    /**
     * Generate a list of test failures for a given sniffed file.
     *
     * @param PHP_CodeSniffer_File $file The file being tested.
     *
     * @return array
     * @throws PHP_CodeSniffer_Exception
     */
    public function generateFailureMessages(PHP_CodeSniffer_File $file)
    {
        $testFile = $file->getFilename();

        $foundErrors      = $file->getErrors();
        $foundWarnings    = $file->getWarnings();
        $expectedErrors   = $this->getErrorList(basename($testFile));
        $expectedWarnings = $this->getWarningList(basename($testFile));

        if (is_array($expectedErrors) === false) {
            throw new PHP_CodeSniffer_Exception('getErrorList() must return an array');
        }

        if (is_array($expectedWarnings) === false) {
            throw new PHP_CodeSniffer_Exception('getWarningList() must return an array');
        }

        /*
            We merge errors and warnings together to make it easier
            to iterate over them and produce the errors string. In this way,
            we can report on errors and warnings in the same line even though
            it's not really structured to allow that.
        */

        $allProblems     = array();
        $failureMessages = array();

        foreach ($foundErrors as $line => $lineErrors) {
            foreach ($lineErrors as $column => $errors) {
                if (isset($allProblems[$line]) === false) {
                    $allProblems[$line] = array(
                                           'expected_errors'   => 0,
                                           'expected_warnings' => 0,
                                           'found_errors'      => array(),
                                           'found_warnings'    => array(),
                                          );
                }

                $foundErrorsTemp = array();
                foreach ($allProblems[$line]['found_errors'] as $foundError) {
                    $foundErrorsTemp[] = $foundError;
                }

                $errorsTemp = array();
                foreach ($errors as $foundError) {
                    $errorsTemp[] = $foundError['message'].' ('.$foundError['source'].')';
                }

                $allProblems[$line]['found_errors'] = array_merge($foundErrorsTemp, $errorsTemp);
            }//end foreach

            if (isset($expectedErrors[$line]) === true) {
                $allProblems[$line]['expected_errors'] = $expectedErrors[$line];
            } else {
                $allProblems[$line]['expected_errors'] = 0;
            }

            unset($expectedErrors[$line]);
        }//end foreach

        foreach ($expectedErrors as $line => $numErrors) {
            if (isset($allProblems[$line]) === false) {
                $allProblems[$line] = array(
                                       'expected_errors'   => 0,
                                       'expected_warnings' => 0,
                                       'found_errors'      => array(),
                                       'found_warnings'    => array(),
                                      );
            }

            $allProblems[$line]['expected_errors'] = $numErrors;
        }

        foreach ($foundWarnings as $line => $lineWarnings) {
            foreach ($lineWarnings as $column => $warnings) {
                if (isset($allProblems[$line]) === false) {
                    $allProblems[$line] = array(
                                           'expected_errors'   => 0,
                                           'expected_warnings' => 0,
                                           'found_errors'      => array(),
                                           'found_warnings'    => array(),
                                          );
                }

                $foundWarningsTemp = array();
                foreach ($allProblems[$line]['found_warnings'] as $foundWarning) {
                    $foundWarningsTemp[] = $foundWarning;
                }

                $warningsTemp = array();
                foreach ($warnings as $warning) {
                    $warningsTemp[] = $warning['message'].' ('.$warning['source'].')';
                }

                $allProblems[$line]['found_warnings'] = array_merge($foundWarningsTemp, $warningsTemp);
            }//end foreach

            if (isset($expectedWarnings[$line]) === true) {
                $allProblems[$line]['expected_warnings'] = $expectedWarnings[$line];
            } else {
                $allProblems[$line]['expected_warnings'] = 0;
            }

            unset($expectedWarnings[$line]);
        }//end foreach

        foreach ($expectedWarnings as $line => $numWarnings) {
            if (isset($allProblems[$line]) === false) {
                $allProblems[$line] = array(
                                       'expected_errors'   => 0,
                                       'expected_warnings' => 0,
                                       'found_errors'      => array(),
                                       'found_warnings'    => array(),
                                      );
            }

            $allProblems[$line]['expected_warnings'] = $numWarnings;
        }

        // Order the messages by line number.
        ksort($allProblems);

        foreach ($allProblems as $line => $problems) {
            $numErrors        = count($problems['found_errors']);
            $numWarnings      = count($problems['found_warnings']);
            $expectedErrors   = $problems['expected_errors'];
            $expectedWarnings = $problems['expected_warnings'];

            $errors      = '';
            $foundString = '';

            if ($expectedErrors !== $numErrors || $expectedWarnings !== $numWarnings) {
                $lineMessage     = "[LINE $line]";
                $expectedMessage = 'Expected ';
                $foundMessage    = 'in '.basename($testFile).' but found ';

                if ($expectedErrors !== $numErrors) {
                    $expectedMessage .= "$expectedErrors error(s)";
                    $foundMessage    .= "$numErrors error(s)";
                    if ($numErrors !== 0) {
                        $foundString .= 'error(s)';
                        $errors      .= implode(PHP_EOL.' -> ', $problems['found_errors']);
                    }

                    if ($expectedWarnings !== $numWarnings) {
                        $expectedMessage .= ' and ';
                        $foundMessage    .= ' and ';
                        if ($numWarnings !== 0) {
                            if ($foundString !== '') {
                                $foundString .= ' and ';
                            }
                        }
                    }
                }

                if ($expectedWarnings !== $numWarnings) {
                    $expectedMessage .= "$expectedWarnings warning(s)";
                    $foundMessage    .= "$numWarnings warning(s)";
                    if ($numWarnings !== 0) {
                        $foundString .= 'warning(s)';
                        if (empty($errors) === false) {
                            $errors .= PHP_EOL.' -> ';
                        }

                        $errors .= implode(PHP_EOL.' -> ', $problems['found_warnings']);
                    }
                }

                $fullMessage = "$lineMessage $expectedMessage $foundMessage.";
                if ($errors !== '') {
                    $fullMessage .= " The $foundString found were:".PHP_EOL." -> $errors";
                }

                $failureMessages[] = $fullMessage;
            }//end if
        }//end foreach

        return $failureMessages;

    }//end generateFailureMessages()


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
    protected abstract function getErrorList($testFile);


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
    protected abstract function getWarningList($testFile);


}//end class

?>

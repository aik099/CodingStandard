<?php
/**
 * A test class for running all PHP_CodeSniffer unit tests.
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

if (defined('PHP_CODESNIFFER_IN_TESTS') === false) {
    define('PHP_CODESNIFFER_IN_TESTS', true);
}

if (defined('PHP_CODESNIFFER_CBF') === false) {
    define('PHP_CODESNIFFER_CBF', false);
}

if (defined('PHP_CODESNIFFER_VERBOSITY') === false) {
    define('PHP_CODESNIFFER_VERBOSITY', 0);
}

define('FULL_PATH', realpath(__DIR__.'/..'));
define('STANDARDS_PATH', FULL_PATH);
define('STANDARD_NAME', 'CodingStandard');

$vendorPath = FULL_PATH.'/vendor';

if (is_dir($vendorPath) === false) {
    echo 'Install dependencies first'.PHP_EOL;
    exit(1);
}

require_once $vendorPath.'/squizlabs/php_codesniffer/autoload.php';

// Defines constants, used in sniffs.
$tokens = new \PHP_CodeSniffer\Util\Tokens();

// Compatibility for PHPUnit < 6 and PHPUnit 6+.
if (class_exists('PHPUnit_Framework_TestSuite') === true && class_exists('PHPUnit\Framework\TestSuite') === false) {
    class_alias('PHPUnit_Framework_TestSuite', 'PHPUnit'.'\Framework\TestSuite');
}

if (class_exists('PHPUnit_Framework_TestCase') === true && class_exists('PHPUnit\Framework\TestCase') === false) {
    class_alias('PHPUnit_Framework_TestCase', 'PHPUnit'.'\Framework\TestCase');
}

if (class_exists('PHPUnit_TextUI_TestRunner') === true && class_exists('PHPUnit\TextUI\TestRunner') === false) {
    class_alias('PHPUnit_TextUI_TestRunner', 'PHPUnit'.'\TextUI\TestRunner');
}

if (class_exists('PHPUnit_Framework_TestResult') === true && class_exists('PHPUnit\Framework\TestResult') === false) {
    class_alias('PHPUnit_Framework_TestResult', 'PHPUnit'.'\Framework\TestResult');
}

// Manually load test case class, because it's not covered by autoloader.
require_once __DIR__.'/AbstractSniffUnitTest.php';

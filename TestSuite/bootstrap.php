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

define('FULL_PATH', realpath(__DIR__.'/..'));
define('STANDARDS_PATH', FULL_PATH);
define('STANDARD_NAME', 'CodingStandard');

$vendorPath = FULL_PATH.'/vendor';

if (is_dir($vendorPath) === false) {
    echo 'Install dependencies first'.PHP_EOL;
    exit(1);
}

require_once $vendorPath.'/autoload.php';

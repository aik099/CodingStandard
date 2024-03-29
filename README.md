# CodingStandard
[![CI](https://github.com/aik099/CodingStandard/actions/workflows/tests.yml/badge.svg)](https://github.com/aik099/CodingStandard/actions/workflows/tests.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aik099/CodingStandard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/aik099/CodingStandard/?branch=master)
[![codecov](https://codecov.io/gh/aik099/CodingStandard/branch/master/graph/badge.svg)](https://codecov.io/gh/aik099/CodingStandard)

[![Latest Stable Version](https://poser.pugx.org/aik099/coding-standard/v/stable.png)](https://packagist.org/packages/aik099/coding-standard)
[![Total Downloads](https://poser.pugx.org/aik099/coding-standard/downloads.png)](https://packagist.org/packages/aik099/coding-standard)

The PHP_CodeSniffer coding standard I'm using on all of my projects.

Standard itself and it's test suite complies [PHPCS standard](https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards/PHPCS).

# Usage
Only PHP_CodeSniffer 3.x and later versions are supported. For PHP_CodeSniffer 1.x and 2.x use "1.0" branch.

1. clone this repository
2. run following command in project directory:
```bash
$> phpcs --standard="/path/to/CodingStandard/CodingStandard" library tests
```
or by make your IDE ([instructions for PhpStorm](http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html)) to check them automatically.

# CodingStandard
[![Build Status](https://travis-ci.org/aik099/CodingStandard.png?branch=master)](https://travis-ci.org/aik099/CodingStandard)
[![HHVM Status](http://hhvm.h4cc.de/badge/aik099/coding-standard.png)](http://hhvm.h4cc.de/package/aik099/coding-standard)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/aik099/CodingStandard/badges/quality-score.png?s=dfdd7644537b5c57bfc551a640d91e645fcff979)](https://scrutinizer-ci.com/g/aik099/CodingStandard/)
[![Coverage Status](https://coveralls.io/repos/aik099/CodingStandard/badge.png?branch=master)](https://coveralls.io/r/aik099/CodingStandard?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/53e1e3f1ebe4a10250000012/badge.svg?style=flat)](https://www.versioneye.com/user/projects/53e1e3f1ebe4a10250000012)

[![Latest Stable Version](https://poser.pugx.org/aik099/coding-standard/v/stable.png)](https://packagist.org/packages/aik099/coding-standard)
[![Total Downloads](https://poser.pugx.org/aik099/coding-standard/downloads.png)](https://packagist.org/packages/aik099/coding-standard)

The PHP_CodeSniffer coding standard I'm using on all of my projects.

Standard itself and it's test suite complies [PHPCS standard](https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards/PHPCS).

# Usage
Only PHP_CodeSniffer 1.x and 2.x versions are supported.

1. clone this repository
2. run following command in project directory:
```bash
$> phpcs --standard="/path/to/CodingStandard/CodingStandard" library tests
```
or by make your IDE ([instructions for PhpStorm](http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html)) to check them automatically.

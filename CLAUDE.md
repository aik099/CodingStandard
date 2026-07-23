# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A PHP_CodeSniffer 3.x coding standard package (`aik099/coding-standard`). It ships a `CodingStandard` ruleset made of custom sniffs plus references to built-in PHPCS/Squiz/Generic sniffs, along with the PHPUnit-based test suite that PHPCS itself uses to validate sniffs (fixture `.inc` files with expected error/warning positions).

Only PHP_CodeSniffer 3.x+ is supported here; the "1.0" branch covers PHPCS 1.x/2.x.

## Commands

Install dependencies:
```bash
composer install
```

Run the full test suite (PHPUnit driving all sniff unit tests):
```bash
vendor/bin/phpunit
```

Run a single sniff's test class:
```bash
vendor/bin/phpunit CodingStandard/Tests/Classes/ClassNamespaceUnitTest.php
```

Check this repo's own source against the standard (dogfooding):
```bash
vendor/bin/phpcs --standard=CodingStandard CodingStandard TestSuite
```

Manually inspect/debug a single sniff against its fixture file (`sniff.name` is the short name after `CodingStandard.`, e.g. `Classes.ClassNamespace`):
```bash
./phpcs-validate.sh Classes.ClassNamespace          # full report
./phpcs-validate.sh Classes.ClassNamespace diff     # generate a patch, apply it, and produce .fixed reference files
```

## Architecture

- **`CodingStandard/ruleset.xml`** — the standard's entry point. It composes this package's own sniffs (`CodingStandard.*`) with rules borrowed from PHPCS's built-in `Generic` and `Squiz` standards, including per-rule severity overrides and `exclude-pattern`s (e.g. member/function naming exceptions for files matching `e_*.php`, or excluding `*Test.php` from one-class-per-file).
- **`CodingStandard/Sniffs/<Category>/<Name>Sniff.php`** — one sniff per file, namespaced `CodingStandard\Sniffs\<Category>`, implementing PHPCS's `Sniff` interface (`register()` returns the token types to listen for; `process()` runs when one is encountered and calls `$phpcsFile->addError()`/`addWarning()`).
- **`CodingStandard/Tests/<Category>/<Name>UnitTest.php`** — the matching test for each sniff, namespaced `CodingStandard\Tests\<Category>`, extending `TestSuite\AbstractSniffUnitTest`. Each implements `getErrorList($testFile)` / `getWarningList($testFile)` mapping line numbers to expected violation counts.
- **`CodingStandard/Tests/<Category>/<Name>UnitTest*.inc`** — fixture files phpcs actually scans; multiple numbered fixtures (`.1.inc`, `.2.inc`, ...) can exist per sniff for different scenarios. A matching `.inc.fixed` file is the expected result after auto-fixing (for sniffs implementing `Fixer`); `phpcs-validate.sh ... diff` is how these `.fixed` files get (re)generated.
- **`TestSuite/bootstrap.php`** and **`TestSuite/AbstractSniffUnitTest.php`** — vendored/adapted from PHP_CodeSniffer's own test harness; this is what makes PHPUnit understand and run `*UnitTest.php` sniff tests found under `CodingStandard/Tests` (see `phpunit.xml.dist`).
- **Naming convention**: every category directory name under `Sniffs/` and `Tests/` must match (e.g. `Sniffs/Commenting/FunctionCommentSniff.php` pairs with `Tests/Commenting/FunctionCommentUnitTest.php` + its `.inc` fixtures), since PHPCS discovers sniffs and matches them to tests by this directory/name convention.

## Adding or changing a sniff

1. Add/modify the sniff class under `CodingStandard/Sniffs/<Category>/`.
2. Add/update the fixture (`.inc`) and unit test (`UnitTest.php`) under `CodingStandard/Tests/<Category>/` with the same base name.
3. Reference new sniffs from `CodingStandard/ruleset.xml` if they should be enabled by default (custom sniffs under `CodingStandard.*` are auto-included; only exceptions/severities/excludes need explicit entries).
4. Run `vendor/bin/phpunit` (or the single test class) to verify expected error/warning lines match.

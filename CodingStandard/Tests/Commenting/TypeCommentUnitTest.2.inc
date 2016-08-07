<?php

/** @var ClassName $global_variable_placed_correctly */
$global_variable_placed_correctly = new ClassName();

/** @var ClassName $global_variable_placed_correctly_with_description */
$global_variable_placed_correctly_with_description = new ClassName();

$global_variable_placed_incorrectly = new ClassName();
/** @var $global_variable_placed_incorrectly ClassName */

$global_variable_placed_incorrectly_with_description = new ClassName();
/** @var $global_variable_placed_incorrectly_with_description ClassName The description */

$global_multi_line_variable_placed_incorrectly =& $event->getObject(
    array('skip_autoload' => true)
);
/** @var $global_multi_line_variable_placed_incorrectly ClassName */

$global_multi_line_variable_placed_incorrectly_with_description =& $event->getObject(
    array('skip_autoload' => true)
);
/** @var $global_multi_line_variable_placed_incorrectly_with_description ClassName */

function test_func1()
{
    $func1_variable_placed_incorrectly = new ClassName();
    /** @var $func1_variable_placed_incorrectly ClassName */
}

function test_func2()
{
    $func2_variable_placed_incorrectly_with_description = new ClassName();
    /** @var $func2_variable_placed_incorrectly_with_description ClassName The description */
}

function test_func3()
{
    $func3_multi_line_variable_placed_incorrectly =& $event->getObject(
        array('skip_autoload' => true)
    );
    /** @var $func3_multi_line_variable_placed_incorrectly ClassName */
}

function test_func4()
{
    $func4_multi_line_variable_placed_incorrectly_with_description =& $event->getObject(
        array('skip_autoload' => true)
    );
    /** @var $func4_multi_line_variable_placed_incorrectly_with_description ClassName */
}

/** @var ClassName $global_variable_name_mismatch */
$global_variable_name = new ClassName();

/** @var ClassName $global_variable_name_mismatch_with_description */
$global_variable_name_with_description = new ClassName();

$a = 5 + 12;
/** @var ClassName $global_variable_without_top_margin */
$global_variable_without_top_margin = new ClassName();

$a = 5 + 12;
/** @var ClassName $global_variable_without_top_margin_with_description The description */
$global_variable_without_top_margin_with_description = new ClassName();

function test_func5()
{
    /** @var ClassName $global_variable_without_top_margin */
    $global_variable_without_top_margin = new ClassName();
}

function test_func6()
{
    /** @var ClassName $global_variable_without_top_margin_with_description The description */
    $global_variable_without_top_margin_with_description = new ClassName();
}

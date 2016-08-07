<?php
/*
 * multi line comment one
 */
$var = new ClassName();

/*
    multi line comment two
*/
$var = new ClassName();

/* single line non-type comment */
$var = new ClassName();

/* @var ClassName $not_doc_block_correct_order */
$not_doc_block_correct_order = new ClassName();

/* @var $not_doc_block_incorrect_order ClassName */
$not_doc_block_incorrect_order = new ClassName();

/**
 * multi line non-type docblock comment
 */
$var = new ClassName();

/** text without tags */
$var = new ClassName();

/** @param ClassName $var Not a type comment we're after. */
$var = new ClassName();

/** @var */
$var = new ClassName();

/** @type ClassName $wrong_tag */
$wrong_tag = new ClassName();

/**  @var  ClassName  $too_much_spaces  */
$too_much_spaces = new ClassName();

/**@var ClassName $too_few_spaces*/
$too_few_spaces = new ClassName();

/** @var ClassNameOnly */
$missing_variable_name = new ClassNameOnly();

/** @var $variable_only */
$variable_only = new MissingClassName();

/** @var ClassName $with_description The description */
$with_description = new ClassName();

/** @var $variable_one $variable_two */
$variable_two = new ClassName();

/** @var ClassNameOne ClassNameTwo */
$variable_one = new ClassNameOne();

/** @var $type_and_variable_swapped ClassName */
$type_and_variable_swapped = new ClassName();

/** @var $type_and_variable_swapped_with_description ClassName The description */
$type_and_variable_swapped_with_description = new ClassName();

/** @var ClassName $everything_correct */
$everything_correct = new ClassName();

/** @var ClassName $everything_correct_with_description The description */
$everything_correct_with_description = new ClassName();

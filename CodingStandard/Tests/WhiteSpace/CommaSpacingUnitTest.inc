<?php
// no errors
$a = array(5, 'a', 10);
$a = array(
	5,
);
func('a', 5);
func($a[5], 'b');
list(, ,) = 5;

// errors
$a = array(5 ,  'a', 10,);
$a = array(
	5  ,   6,
);
func('a'  ,  5);
func($a[5] ,  'b');
list( , , ) = 5;

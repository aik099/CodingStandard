<?php

function simpleAssignment($param1, $param2)
{
    $param1 = 'new value' . $param1;

    return $param2;
}

function referenceAssignmentAllowed($param1, &$param2)
{
    $param2 = 'new value';

    return $param1;
}

function compoundAssignment($param1)
{
    $param1 .= 'appended value';
}

function unrelatedVariableAssignment($param1)
{
    $localVariable = 'value';

    return $param1 . $localVariable;
}

interface InterfaceWithMethod
{
    public function noBody($param1);
}

abstract class ParentClass
{
    abstract public function abstractMethod($param1);
}

class ClassWithMethod
{
    public function methodWithAssignment($param1, $param2)
    {
        $param1 = 'new value';
        $param2 = 'another value';
    }
}

function multipleAssignmentsOfSameParameter($param1)
{
    $param1 = 'first value';
    $param1 = 'second value';
}

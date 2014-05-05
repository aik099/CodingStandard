<?php
function validNoBlankAfterScopeOpen()
{
    return;
}

function invalidBlankAfterScopeOpenOne()
{

    return;
}

function invalidBlankAfterScopeOpenTwo()
{


    return;
}

function validFunctionReturnThree()
{
    echo '';

    return;
}

function invalidFunctionReturnOne()
{
    echo '';
    return;
}

function validSwitchStatement()
{
    switch ($condition) {
        case 'foo':
            return true;

        default:
            return false;
    }
}

function invalidSwitchStatementOne()
{
    switch ($condition) {
        case 'foo':

            return true;

        default:

            return false;
    }
}

function invalidSwitchStatementTwo()
{
    switch ($condition) {
        case 'foo':

            return true;

        default:
            return false;
    }
}

function invalidSwitchStatementThree()
{
    switch ($condition) {
        case 'foo':
            if ($something) {
                return false;
            }
            return true;

        default:

            return false;
    }
}

function validReturnFromIf()
{
    if ($condition) {
        return true;
	} elseif ($condition) {
        return false;
    }
    else {
        return 5;
    }
}

function tooMuchBlankLinesOne()
{
    echo "";


    return;
}

function loopTesting()
{
	for ($i = 0; $i < 5; $i++) {
        return 'ok';

        if (false) {
            return 'ok';
        }
        return true;
    }

    do {

        return $ret;
    } while (true);

    foreach ($test as $key) {
        return;
    }

}

function validWithPrecedingInlineComment()
{
    // This is allowed.
    return;
}

function invalidWithPrecedingInlineComment()
{

    // This is not allowed.
    return;
}

function invalidWithAlmostPrecedingInlineComment()
{
    // This is not allowed.

    return;
}

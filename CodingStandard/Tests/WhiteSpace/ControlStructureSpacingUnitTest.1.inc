<?php
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}

if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}

if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}

if ( $a ) {
    $a = 'b';
}
else {
    $a = 'b';
}

if ( $a ) {
    $a = 'b';
}

while ( $a ) {
    $a = 'b';
}

do {
    $a = 'b';
} while ( $a );

foreach ( $a as $b ) {
    $e = 'b';
}

foreach ( $a as $b => $c ) {
    $e = 'b';
}

for ( $i = 0; $i < count($array); $i++ ) {
    $e = 'b';
}

switch ( $a ) {
    default:
}

try {
    $a = 'b';
}
catch ( Exception $e ) {
    $c = 'd';
}

if ($a) {

    $a = 'b';

}
elseif ($b) {

    $a = 'b';

}
elseif ($b) {

    $a = 'b';

}
else {

    $a = 'b';

}

if ($a) {

    $a = 'b';

}
elseif ($b) {

    $a = 'b';

}
elseif ($b) {

    $a = 'b';

}

if ($a) {

    $a = 'b';

}
elseif ($b) {

    $a = 'b';

}
else {

    $a = 'b';

}

if ($a) {

    $a = 'b';

}
else {

    $a = 'b';

}

    if ($a) {

        $a = 'b';

    }

while ($a) {

    $a = 'b';

}

do {

    $a = 'b';

} while ($a);

foreach ($a as $b) {

    $e = 'b';

}

foreach ($a as $b => $c) {

    $e = 'b';

}

for ($i = 0; $i < count($array); $i++) {

    $e = 'b';

}

switch ($a) {

    default:

}

try {

    $a = 'b';

}
catch (Exception $e) {

    $c = 'd';

}

if ( $a ) {

}
elseif ( $b ) {

}
elseif ( $b ) {

}
else {

}

if ( $a ) {

}
elseif ( $b ) {

}
elseif ( $b ) {

}

if ( $a ) {

}
elseif ( $b ) {

}
else {

}

if ( $a ) {

}
else {

}

if ( $a ) {

}

while ( $a ) {

}

do {

} while ( $a );

foreach ( $a as $b ) {

}

foreach ( $a as $b => $c ) {

}

for ( $i = 0; $i < count($array); $i++ ) {

}

switch ( $a ) {

}

try {

}
catch ( Exception $e ) {

}

switch ( $a ) {
    case 'if_elseif_elseif_else':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    case 'if_elseif_elseif':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        break;

    case 'if_elseif_else':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    case 'if_else':
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    case 'if_else':
        if ( $a ) {
            $a = 'b';
        }
        break;

    case 'while':
        while ( $a ) {
            $a = 'b';
        }
        break;

    case 'do':
        do {
            $a = 'b';
        } while ( $a );
        break;

    case 'foreach_value':
        foreach ( $a as $b ) {
            $e = 'b';
        }
        break;

    case 'foreach_key_value':
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }
        break;

    case 'for':
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }
        break;

    case 'switch':
        switch ( $a ) {
            default:
        }
        break;

    case 'try/catch':
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }
        break;
}

switch ( $a ) {
    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        break;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    default:
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        break;

    default:
        if ( $a ) {
            $a = 'b';
        }
        break;

    default:
        while ( $a ) {
            $a = 'b';
        }
        break;

    default:
        do {
            $a = 'b';
        } while ( $a );
        break;

    default:
        foreach ( $a as $b ) {
            $e = 'b';
        }
        break;

    default:
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }
        break;

    default:
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }
        break;

    default:
        switch ( $a ) {
            default:
        }
        break;

    default:
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }
        break;
}

switch ( $a ) {
    case 'if_elseif_elseif_else':

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    case 'if_elseif_elseif':

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }

        break;

    case 'if_elseif_else':

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    case 'if_else':

        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    case 'if_else':

        if ( $a ) {
            $a = 'b';
        }

        break;

    case 'while':

        while ( $a ) {
            $a = 'b';
        }

        break;

    case 'do':

        do {
            $a = 'b';
        } while ( $a );

        break;

    case 'foreach_value':

        foreach ( $a as $b ) {
            $e = 'b';
        }

        break;

    case 'foreach_key_value':

        foreach ( $a as $b => $c ) {
            $e = 'b';
        }

        break;

    case 'for':

        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }

        break;

    case 'switch':

        switch ( $a ) {
            default:
        }

        break;

    case 'try/catch':

        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }

        break;
}

switch ( $a ) {
    default:

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    default:

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }

        break;

    default:

        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    default:

        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        break;

    default:

        if ( $a ) {
            $a = 'b';
        }

        break;

    default:

        while ( $a ) {
            $a = 'b';
        }

        break;

    default:

        do {
            $a = 'b';
        } while ( $a );

        break;

    default:

        foreach ( $a as $b ) {
            $e = 'b';
        }

        break;

    default:

        foreach ( $a as $b => $c ) {
            $e = 'b';
        }

        break;

    default:

        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }

        break;

    default:

        switch ( $a ) {
            default:
        }

        break;

    default:

        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }

        break;
}

$a = 5;
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
$a = 5;
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
$a = 5;
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
$a = 5;
if ( $a ) {
    $a = 'b';
}
else {
    $a = 'b';
}
$a = 5;
if ( $a ) {
    $a = 'b';
}
$a = 5;
while ( $a ) {
    $a = 'b';
}
$a = 5;
do {
    $a = 'b';
} while ( $a );
$a = 5;
foreach ( $a as $b ) {
    $e = 'b';
}
$a = 5;
foreach ( $a as $b => $c ) {
    $e = 'b';
}
$a = 5;
for ( $i = 0; $i < count($array); $i++ ) {
    $e = 'b';
}
$a = 5;
switch ( $a ) {
    default:
}
$a = 5;
try {
    $a = 'b';
}
catch ( Exception $e ) {
    $c = 'd';
}
$a = 5;

// Ignore blank lines at the edge of function.
function test()
{

    if ( $a ) {
        $a = 'b';
    }
    elseif ( $b ) {
        $a = 'b';
    }
    elseif ( $b ) {
        $a = 'b';
    }
    else {
        $a = 'b';
    }

}

function test()
{

    if ( $a ) {
        $a = 'b';
    }
    elseif ( $b ) {
        $a = 'b';
    }
    elseif ( $b ) {
        $a = 'b';
    }

}

function test()
{

    if ( $a ) {
        $a = 'b';
    }
    elseif ( $b ) {
        $a = 'b';
    }
    else {
        $a = 'b';
    }

}

function test()
{

    if ( $a ) {
        $a = 'b';
    }
    else {
        $a = 'b';
    }

}

function test()
{

    if ( $a ) {
        $a = 'b';
    }

}

function test()
{

    while ( $a ) {
        $a = 'b';
    }

}

function test()
{

    do {
        $a = 'b';
    } while ( $a );

}

function test()
{

    foreach ( $a as $b ) {
        $e = 'b';
    }

}

function test()
{

    foreach ( $a as $b => $c ) {
        $e = 'b';
    }

}

function test()
{

    for ( $i = 0; $i < count($array); $i++ ) {
        $e = 'b';
    }

}

function test()
{

    switch ( $a ) {
        default:
    }

}

function test()
{

    try {
        $a = 'b';
    }
    catch ( Exception $e ) {
        $c = 'd';
    }

}

// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.

// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
// After Comment.

// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.

// Before comment.
if ( $a ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.

// Before comment.
if ( $a ) {
    $a = 'b';
}
// After Comment.

// Before comment.
while ( $a ) {
    $a = 'b';
}
// After Comment.

// Before comment.
do {
    $a = 'b';
} while ( $a );
// After Comment.

// Before comment.
foreach ( $a as $b ) {
    $e = 'b';
}
// After Comment.

// Before comment.
foreach ( $a as $b => $c ) {
    $e = 'b';
}
// After Comment.

// Before comment.
for ( $i = 0; $i < count($array); $i++ ) {
    $e = 'b';
}
// After Comment.

// Before comment.
switch ( $a ) {
    default:
}
// After Comment.

// Before comment.
try {
    $a = 'b';
}
catch ( Exception $e ) {
    $c = 'd';
}
// After Comment.

$a = 5;
// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
if ( $a ) {
    $a = 'b';
}
else {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
if ( $a ) {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
while ( $a ) {
    $a = 'b';
}
// After Comment.
$a = 5;
// Before comment.
do {
    $a = 'b';
} while ( $a );
// After Comment.
$a = 5;
// Before comment.
foreach ( $a as $b ) {
    $e = 'b';
}
// After Comment.
$a = 5;
// Before comment.
foreach ( $a as $b => $c ) {
    $e = 'b';
}
// After Comment.
$a = 5;
// Before comment.
for ( $i = 0; $i < count($array); $i++ ) {
    $e = 'b';
}
// After Comment.
$a = 5;
// Before comment.
switch ( $a ) {
    default:
}
// After Comment.
$a = 5;
// Before comment.
try {
    $a = 'b';
}
catch ( Exception $e ) {
    $c = 'd';
}
// After Comment.
$a = 5;

$a = 5;
// Before comment.

if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

if ( $a ) {
    $a = 'b';
}
elseif ( $b ) {
    $a = 'b';
}
else {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

if ( $a ) {
    $a = 'b';
}
else {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

if ( $a ) {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

while ( $a ) {
    $a = 'b';
}

// After Comment.
$a = 5;
// Before comment.

do {
    $a = 'b';
} while ( $a );

// After Comment.
$a = 5;
// Before comment.

foreach ( $a as $b ) {
    $e = 'b';
}

// After Comment.
$a = 5;
// Before comment.

foreach ( $a as $b => $c ) {
    $e = 'b';
}

// After Comment.
$a = 5;
// Before comment.

for ( $i = 0; $i < count($array); $i++ ) {
    $e = 'b';
}

// After Comment.
$a = 5;
// Before comment.

switch ( $a ) {
    default:
}

// After Comment.
$a = 5;
// Before comment.

try {
    $a = 'b';
}
catch ( Exception $e ) {
    $c = 'd';
}

// After Comment.
$a = 5;

if ( $a ) {
    $a = 'b';
}

if ( $a ) {
    $a = 'b';
} elseif ( $b ) {
    $b = 'c';
} else {
    $d = 'e';
}

try {
    $a = 'b';
} catch ( Exception $e ) {
    $c = 'd';
}

if ( $a ) { // comment
    $a = 'b';
}
elseif ( $b ) { // comment
    $a = 'b';
}
elseif ( $b ) { // comment
    $a = 'b';
}
else { // comment
    $a = 'b';
}

while ( $a ) { // comment
    $a = 'b';
}

do { // comment
    $a = 'b';
} while ( $a );

foreach ( $a as $b => $c ) { // comment
    $e = 'b';
}

for ( $i = 0; $i < count($array); $i++ ) { // comment
    $e = 'b';
}

switch ( $a ) { // comment
    default:
}

try { // comment
    $a = 'b';
}
catch ( Exception $e ) { // comment
    $c = 'd';
}

// Empty line before "return" in a "case" of "switch" doesn't trigger an error.
switch ( $a ) {
    case 'if':
        if ( $a ) {
            $a = 'b';
        }

        return 5;

    case 'if_elseif':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }

        return 5;

    case 'if_elseif_else':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        return 5;

    case 'if_else':
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        return 5;

    case 'while':
        while ( $a ) {
            $a = 'b';
        }

        return 5;

    case 'do':
        do {
            $a = 'b';
        } while ( $a );

        return 5;

    case 'foreach_value':
        foreach ( $a as $b ) {
            $e = 'b';
        }

        return 5;

    case 'foreach_key_value':
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }

        return 5;

    case 'for':
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }

        return 5;

    case 'switch':
        switch ( $a ) {
            default:
        }

        return 5;

    case 'try/catch':
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }

        return 5;
}

// Empty line before "return" in a "default" of "switch" doesn't trigger an error.
switch ( $a ) {
    default:
        if ( $a ) {
            $a = 'b';
        }

        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }

        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }

        return 5;

    default:
        while ( $a ) {
            $a = 'b';
        }

        return 5;

    default:
        do {
            $a = 'b';
        } while ( $a );

        return 5;

    default:
        foreach ( $a as $b ) {
            $e = 'b';
        }

        return 5;

    default:
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }

        return 5;

    default:
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }

        return 5;

    default:
        switch ( $a ) {
            default:
        }

        return 5;

    default:
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }

        return 5;
}

// No empty line before "return" in a "case" of "switch" triggers an error.
switch ( $a ) {
    case 'if':
        if ( $a ) {
            $a = 'b';
        }
        return 5;

    case 'if_elseif':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        return 5;

    case 'if_elseif_else':
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        return 5;

    case 'if_else':
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        return 5;

    case 'while':
        while ( $a ) {
            $a = 'b';
        }
        return 5;

    case 'do':
        do {
            $a = 'b';
        } while ( $a );
        return 5;

    case 'foreach_value':
        foreach ( $a as $b ) {
            $e = 'b';
        }
        return 5;

    case 'foreach_key_value':
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }
        return 5;

    case 'for':
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }
        return 5;

    case 'switch':
        switch ( $a ) {
            default:
        }
        return 5;

    case 'try/catch':
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }
        return 5;
}

// No empty line before "return" in a "default" of "switch" triggers an error.
switch ( $a ) {
    default:
        if ( $a ) {
            $a = 'b';
        }
        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        elseif ( $b ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        return 5;

    default:
        if ( $a ) {
            $a = 'b';
        }
        else {
            $a = 'b';
        }
        return 5;

    default:
        while ( $a ) {
            $a = 'b';
        }
        return 5;

    default:
        do {
            $a = 'b';
        } while ( $a );
        return 5;

    default:
        foreach ( $a as $b ) {
            $e = 'b';
        }
        return 5;

    default:
        foreach ( $a as $b => $c ) {
            $e = 'b';
        }
        return 5;

    default:
        for ( $i = 0; $i < count($array); $i++ ) {
            $e = 'b';
        }
        return 5;

    default:
        switch ( $a ) {
            default:
        }
        return 5;

    default:
        try {
            $a = 'b';
        }
        catch ( Exception $e ) {
            $c = 'd';
        }
        return 5;
}

?>

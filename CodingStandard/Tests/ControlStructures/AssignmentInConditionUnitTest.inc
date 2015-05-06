<?php

// correct
if ($a == 5) {

} elseif ($b == 6) {

}

if ($a == 5 || ($b == 6)) {

} elseif ($b == 6 || ($b == 6)) {

}

// incorrect
if ($a = 5) {

} elseif ($b = 6) {

}

if ($a == 5 || ($b = 6)) {

} elseif ($b == 6 || ($b = 6)) {

}

if ($a = 5 || ($b = 6)) {

} elseif ($b = 6 || ($b = 6)) {

}

<?php
class Test {

    function correctFunction($param) {
        echo 'ok';
        parent::correctFunction();
        echo 'test';
    }

    function incorrectFunction($param) {
        echo 'ok';
        parent::correctFunction();
        echo 'test';
    }
}

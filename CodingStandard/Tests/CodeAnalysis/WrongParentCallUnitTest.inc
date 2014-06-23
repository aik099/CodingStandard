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

        $fields['UserType']['options'] = array(
            CustomUserType::PARENT => 'lc_opt_UserTypeParent',
            CustomUserType::CAREGIVER => 'lc_opt_UserTypeCaregiver',
        );
    }

}

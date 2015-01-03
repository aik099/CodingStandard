<?php

    if ( $a ) {

        // line before

        $b = 1;

        // line after

    }

    if ( $a ) {
        /* block comment before */

        if ( $b ) {
            $content = 'new';
        }

        /* block comment after */
    }



if ( $address_id == -1 ) {
    // create new address and make it "last used as shipping".
    if ( $oaddr->Create() ) {
        $address_id = $oaddr->GetID();
    }

    /*$this->Conn->doInsert($fields_hash, TABLE_PREFIX.'Addresses');
    $address_id = $this->Conn->getInsertID();*/
}
elseif ( $address_id > 0 ) {
    // update current address and make it last used as shipping.
    /*$oaddr->SetId($address_id);
    $this->Application->SetVar('organization');*/
    if ( $oaddr->Update() ) {
        $organization_address_id = $oaddr->GetDBField('OrganizationAddressId');
    }
    else {
        $address_id = -1;
    }

    // $this->Conn->doUpdate($fields_hash, TABLE_PREFIX.'Addresses', 'AddressId = '.$address_id);

}

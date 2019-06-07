<?php


namespace Kinicart\Test\Services\Security;


use Kinicart\Objects\Account\Contact;

class TestMethodService {


    /**
     * Normal unrestricted method
     */
    public function normalMethod() {
        $contact = new Contact("Mark R", "Test Organisation","My Lane","My Shire","Oxford",
            "Oxon","OX4 7YY","GB",null,"test@test.com",1,Contact::ADDRESS_TYPE_GENERAL);
        $contact->save();
    }


    /**
     * Object interceptor disabled method
     *
     * @objectInterceptorDisabled
     */
    public function objectInterceptorDisabledMethod() {
        $contact = new Contact("Mark R", "Test Organisation","My Lane","My Shire","Oxford",
            "Oxon","OX4 7YY","GB",null,"test@test.com",1,Contact::ADDRESS_TYPE_GENERAL);
        $contact->save();
    }





}

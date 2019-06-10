<?php


namespace Kinicart\Test\Services\Security;


use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\Contact;

class TestMethodService {


    /**
     * Normal unrestricted method
     */
    public function normalMethod() {
        $contact = new Contact("Mark R", "Test Organisation", "My Lane", "My Shire", "Oxford",
            "Oxon", "OX4 7YY", "GB", null, "test@test.com", 1, Contact::ADDRESS_TYPE_GENERAL);
        $contact->save();
    }


    /**
     * Object interceptor disabled method
     *
     * @objectInterceptorDisabled
     */
    public function objectInterceptorDisabledMethod() {
        $contact = new Contact("Mark R", "Test Organisation", "My Lane", "My Shire", "Oxford",
            "Oxon", "OX4 7YY", "GB", null, "test@test.com", 1, Contact::ADDRESS_TYPE_GENERAL);
        $contact->save();
    }


    /**
     *
     * @hasPrivilege deletedata
     *
     * @return string
     */
    public function accountPermissionRestricted() {
        return "OK";
    }


    /**
     * @hasPrivilege editdata($accountId)
     *
     * @param $accountId
     * @param $newName
     */
    public function otherAccountPermissionRestricted($accountId, $newName) {
        return "DONE";
    }


    /**
     * Special magic logged in constant
     *
     * @param $param1
     * @param null $accountId
     */
    public function loggedInAccountInjection($param1, $accountId = Account::LOGGED_IN_ACCOUNT) {
        return array($param1, $accountId);
    }

}

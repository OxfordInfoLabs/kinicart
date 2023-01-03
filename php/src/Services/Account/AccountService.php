<?php


namespace Kinicart\Services\Account;


use Kiniauth\Objects\Account\Contact;
use Kiniauth\Services\Account\ContactService;
use Kinicart\Objects\Account\Account;
use Kinicart\ValueObjects\Account\BillingContact;

class AccountService {


    /**
     * @var ContactService
     */
    private $contactService;


    /**
     * AccountService constructor.
     *
     * @param ContactService $contactService
     */
    public function __construct($contactService) {
        $this->contactService = $contactService;
    }


    /**
     * Return the billing contact for the supplied account
     *
     * @return BillingContact
     */
    public function getBillingContact($accountId = Account::LOGGED_IN_ACCOUNT) {
        $contacts = $this->contactService->getContacts("BILLING", $accountId);
        if (sizeof($contacts) > 0) {
            $contact = $contacts[0];
            return new BillingContact($contact->getName(), $contact->getOrganisation(), $contact->getStreet1(),
                $contact->getStreet2(), $contact->getCity(), $contact->getCounty(), $contact->getPostcode(), $contact->getCountryCode());
        } else {
            return null;
        }
    }


    /**
     * Update the billing contact for the supplied account
     *
     * @param BillingContact $billingContact
     * @param string $accountId
     */
    public function updateBillingContact($billingContact, $accountId = Account::LOGGED_IN_ACCOUNT) {

        $contacts = $this->contactService->getContacts("BILLING", $accountId);

        if (sizeof($contacts) > 0) {
            $contact = $contacts[0];
            $contact->setName($billingContact->getName());
            $contact->setOrganisation($billingContact->getOrganisation());
            $contact->setStreet1($billingContact->getStreet1());
            $contact->setStreet2($billingContact->getStreet2());
            $contact->setCity($billingContact->getCity());
            $contact->setCounty($billingContact->getCounty());
            $contact->setPostcode($billingContact->getPostcode());
            $contact->setCountryCode($billingContact->getCountryCode());

        } else {
            $contact = new Contact($billingContact->getName(), $billingContact->getOrganisation(),
                $billingContact->getStreet1(), $billingContact->getStreet2(), $billingContact->getCity(),
                $billingContact->getCounty(), $billingContact->getPostcode(), $billingContact->getCountryCode(),
                null, null, $accountId, "BILLING");
        }

        $this->contactService->saveContact($contact);
    }


}
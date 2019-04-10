<?php


namespace Kinicart\Objects\Account;


use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 * Contact object for use across the system.
 *
 * Class Contact
 */
class Contact extends ActiveRecord {


    /**
     * Auto increment id.
     *
     * @var integer
     */
    protected $id;


    /**
     * Owner account id.
     *
     * @var integer
     * @validation required.
     */
    protected $accountId;


    /**
     * A string type for the contact.  These are freeform for application specific
     * typing with a single core type of GENERAL for general address book contacts.
     * Defaults to general
     *
     * @var string
     * @validation required
     */
    private $type = self::ADDRESS_TYPE_GENERAL;


    /**
     * Name for the contact
     *
     * @var string
     */
    private $name;


    /**
     * Optional organisation name
     *
     * @var string
     */
    private $organisation;


    /**
     * Street 1
     *
     * @var string
     * @validation required
     */
    private $street1;


    /**
     * Street 2
     *
     * @var string
     */
    private $street2;


    /**
     * City
     *
     * @var string
     * @validation required
     */
    private $city;


    /**
     * County
     *
     * @var string
     */
    private $county;


    /**
     * Postcode
     *
     * @var string
     */
    private $postcode;

    /**
     * Country code (2 Letter)
     *
     * @var string
     * @validation required
     */
    private $countryCode;


    /**
     * Full telephone number
     *
     * @var string
     */
    private $telephoneNumber;


    /**
     * Email address.
     *
     * @var string
     * @validation email
     */
    private $emailAddress;


    const ADDRESS_TYPE_GENERAL = "GENERAL";


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOrganisation() {
        return $this->organisation;
    }

    /**
     * @param string $organisation
     */
    public function setOrganisation($organisation) {
        $this->organisation = $organisation;
    }

    /**
     * @return string
     */
    public function getStreet1() {
        return $this->street1;
    }

    /**
     * @param string $street1
     */
    public function setStreet1($street1) {
        $this->street1 = $street1;
    }

    /**
     * @return string
     */
    public function getStreet2() {
        return $this->street2;
    }

    /**
     * @param string $street2
     */
    public function setStreet2($street2) {
        $this->street2 = $street2;
    }

    /**
     * @return string
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCounty() {
        return $this->county;
    }

    /**
     * @param string $county
     */
    public function setCounty($county) {
        $this->county = $county;
    }

    /**
     * @return string
     */
    public function getPostcode() {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode) {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getCountryCode() {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode) {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber() {
        return $this->telephoneNumber;
    }

    /**
     * @param string $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber) {
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * @return string
     */
    public function getEmailAddress() {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress) {
        $this->emailAddress = $emailAddress;
    }


}

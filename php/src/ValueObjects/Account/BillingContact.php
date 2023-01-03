<?php


namespace Kinicart\ValueObjects\Account;


class BillingContact {

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
     * @required
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
     * @required
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
     * @required
     */
    private $countryCode;

    /**
     * BillingContact constructor.
     *
     * @param string $name
     * @param string $organisation
     * @param string $street1
     * @param string $street2
     * @param string $city
     * @param string $county
     * @param string $postcode
     * @param string $countryCode
     */
    public function __construct($name, $organisation = null,
                                $street1 = null, $street2 = null,
                                $city = null, $county = null, $postcode = null, $countryCode = null) {
        $this->name = $name;
        $this->organisation = $organisation;
        $this->street1 = $street1;
        $this->street2 = $street2;
        $this->city = $city;
        $this->county = $county;
        $this->postcode = $postcode;
        $this->countryCode = $countryCode;
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


}
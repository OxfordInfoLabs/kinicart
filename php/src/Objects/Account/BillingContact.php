<?php


namespace Kinicart\Objects\Account;


use Kiniauth\Objects\Account\Contact;
use Kinicart\Objects\Pricing\VATRate;

/**
 * Class BillingContact
 *
 * @table ka_contact
 */
class BillingContact extends Contact {

    /**
     * @var VATRate
     *
     * @manyToOne
     * @parentJoinColumns country_code
     * @readOnly
     */
    private $vatRate;


    /**
     * @return float
     */
    public function getVatRatePercentage() {
        return $this->vatRate ? $this->vatRate->getVatPercentage() : 0;
    }


}
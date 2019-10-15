<?php


namespace Kinicart\Objects\Payment;

use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class PaymentMethod
 * @package Kinicart\Objects\Payment
 *
 * @table kc_payment_method
 */
class PaymentMethod extends ActiveRecord {

    const STRIPE_PAYMENT_METHOD = 'stripe';
    const PAYPAL_PAYMENT_METHOD = 'paypal';

    /**
     * @primaryKey
     *
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $accountId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     * @json
     * @sqlType LONGTEXT
     */
    private $data;

    /**
     * @var boolean
     */
    private $defaultMethod;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
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
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isDefaultMethod() {
        return $this->defaultMethod;
    }

    /**
     * @param bool $defaultMethod
     */
    public function setDefaultMethod($defaultMethod) {
        $this->defaultMethod = $defaultMethod;
    }


}

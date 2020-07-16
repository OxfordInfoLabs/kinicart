<?php


namespace Kinicart\Objects\Payment;

use Kinicart\Objects\Payment\Stripe\StripePayment;
use Kinikit\Core\Binding\ObjectBinder;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class PaymentMethod
 * @package Kinicart\Objects\Payment
 *
 * @table kc_payment_method
 * @generate
 */
class PaymentMethod extends ActiveRecord {

    const STRIPE_PAYMENT_METHOD = 'stripe';
    const PAYPAL_PAYMENT_METHOD = 'paypal';

    const METHOD_CLASSES = ['stripe' => StripePayment::class];


    /**
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
     * @var string[]
     * @json
     * @sqlType LONGTEXT
     */
    private $payment;

    /**
     * @var boolean
     */
    private $defaultMethod;

    public function __construct($accountId = null, $type = null, $payment = null, $defaultMethod = null) {
        $this->accountId = $accountId;
        $this->type = $type;
        $this->payment = $payment;
        $this->defaultMethod = $defaultMethod;
    }

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
     * @return Payment
     */
    public function getPayment() {

        $mappedClass = self::METHOD_CLASSES[$this->type];

        /**
         * @var $binder ObjectBinder
         */
        $binder = Container::instance()->get(ObjectBinder::class);
        return $binder->bindFromArray($this->payment, $mappedClass);

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

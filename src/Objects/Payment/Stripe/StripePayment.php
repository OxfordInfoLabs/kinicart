<?php


namespace Kinicart\Objects\Payment\Stripe;

use Kinicart\Objects\Payment\Payment;
use Kinicart\Services\Payment\Stripe\StripeService;
use Kinikit\Core\DependencyInjection\Container;

/**
 * Definition of the stripe data stored against a payment method
 *
 * Class StripePayment
 * @package Kinicart\Objects\Payment\Stripe
 *
 * @noGenerate
 */
class StripePayment implements Payment {

    private $id;

    private $billingDetails;

    private $card;

    private $created;

    private $customer;

    private $metadata;

    private $type;

    /**
     * @param $amount
     * @param $currency
     * @return mixed|void
     */
    public function charge($amount, $currency) {
        $stripeService = Container::instance()->get(StripeService::class);
        $result = $stripeService->createPaymentIntent(
            ($amount * 100),
            $currency,
            $this->customer,
            $this->id
        );

        if (isset($result["charges"]) && sizeof($result["charges"]) > 0) {
            $paymentData = [
                "paymentIntent" => $result["id"],
                "amount" => $result["amount"]
            ];

            if (sizeof($result["charges"]["data"]) > 0) {
                $paymentData["reference"] = $result["charges"]["data"][0]["id"];
                $paymentData["captured"] = $result["charges"]["data"][0]["captured"];
            }
            return $paymentData;
        }
        return null;

    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getBillingDetails() {
        return $this->billingDetails;
    }

    /**
     * @param mixed $billingDetails
     */
    public function setBillingDetails($billingDetails) {
        $this->billingDetails = $billingDetails;
    }

    /**
     * @return mixed
     */
    public function getCard() {
        return $this->card;
    }

    /**
     * @param mixed $card
     */
    public function setCard($card) {
        $this->card = $card;
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getCustomer() {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer) {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     */
    public function setMetadata($metadata) {
        $this->metadata = $metadata;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }


}

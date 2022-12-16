<?php


namespace Kinicart\ValueObjects\Payment;


/**
 * Class PaymentResult
 * @package Kinicart\ValueObjects\Payment
 *
 * Rational payment result
 */
class PaymentResult {

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var string
     */
    private $errorMessage;


    /**
     * @var mixed
     */
    private $additionalPaymentData;


    // Status constants
    const STATUS_SUCCESS = "Success";
    const STATUS_FAILED = "Failed";

    /**
     * PaymentResult constructor.
     *
     * @param string $status
     * @param string $reference
     * @param string $errorMessage
     * @param mixed $additionalPaymentData
     */
    public function __construct($status, $reference = null, $errorMessage = null, $additionalPaymentData = null) {
        $this->status = $status;
        $this->reference = $reference;
        $this->errorMessage = $errorMessage;
        $this->additionalPaymentData = $additionalPaymentData;
    }


    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @return mixed
     */
    public function getAdditionalPaymentData() {
        return $this->additionalPaymentData;
    }


}
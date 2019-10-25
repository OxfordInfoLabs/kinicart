<?php


namespace Kinicart\Objects\Order;


use Kiniauth\Objects\Account\Contact;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Cart\CartItem;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Class Order
 * @package Kinicart\Objects\Order
 *
 * @table kc_order
 * @generate
 */
class Order extends ActiveRecord {

    const ORDER_STATUS_COMPLETED = 'completed';
    const ORDER_STATUS_FAILED = 'failed';

    /**
     * @primaryKey
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $accountId;

    /**
     * @sqlType DATETIME
     */
    private $date;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var float
     */
    private $subtotal;

    /**
     * @var float
     */
    private $taxes;

    /**
     * @var float
     */
    private $total;

    /**
     * @var string
     */
    private $buyerName;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string[]
     * @json
     * @sqlType TEXT
     */
    private $paymentData;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string[]
     * @json
     * @sqlType LONGTEXT
     */
    private $orderLines;

    /**
     * Order constructor.
     * @param Contact $contact
     * @param Cart $cart
     * @param mixed $paymentData
     * @param mixed $currency
     */
    public function __construct($contact = null, $cart = null, $paymentData = null, $currency = null) {
        if ($contact) {
            $this->accountId = $contact->getAccountId();
            $this->address = $contact->getHtmlAddressLinesString();
            $this->buyerName = $contact->getName();

        }
        // process the cart
        if ($cart) {
            $this->orderLines = [];
            /** @var CartItem $cartItem */
            foreach ($cart->getItems() as $cartItem) {
                $this->orderLines[] = [
                    "title" => $cartItem->getTitle(),
                    "description" => $cartItem->getDescription(),
                    "quantity" => $cartItem->getQuantity(),
                    "amount" => $cartItem->getUnitPrice($currency),
                    "subItems" => $cartItem->getSubItems()
                ];
            }

//            $this->subtotal = $subtotal;
            $this->taxes = 0.20;
            $this->total = $cart->getTotal();
        }

        $this->date = date("Y-m-d H:i:s");
        $this->currency = $currency;

        $this->paymentData = $paymentData;
        $this->status = ($paymentData && isset($paymentData["reference"])) ? self::ORDER_STATUS_COMPLETED : self::ORDER_STATUS_FAILED;
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
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getSubtotal() {
        return $this->subtotal;
    }

    /**
     * @param float $subtotal
     */
    public function setSubtotal($subtotal) {
        $this->subtotal = $subtotal;
    }

    /**
     * @return float
     */
    public function getTaxes() {
        return $this->taxes;
    }

    /**
     * @param float $taxes
     */
    public function setTaxes($taxes) {
        $this->taxes = $taxes;
    }

    /**
     * @return float
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total) {
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

    /**
     * @return string[]
     */
    public function getPaymentData() {
        return $this->paymentData;
    }

    /**
     * @param string[] $paymentData
     */
    public function setPaymentData($paymentData) {
        $this->paymentData = $paymentData;
    }

    /**
     * @return string[]
     */
    public function getOrderLines() {
        return $this->orderLines;
    }

    /**
     * @param string[] $orderLines
     */
    public function setOrderLines($orderLines) {
        $this->orderLines = $orderLines;
    }

    /**
     * @return string
     */
    public function getBuyerName() {
        return $this->buyerName;
    }

    /**
     * @param string $buyerName
     */
    public function setBuyerName($buyerName) {
        $this->buyerName = $buyerName;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }


}

<?php


namespace Kinicart\Objects\Order;


use Kiniauth\Objects\Account\Contact;
use Kinicart\Objects\Account\Account;
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
     * @param Account $account
     */
    public function __construct($contact = null, $cart = null, $paymentData = null, $account = null) {
        if ($contact) {
            $this->accountId = $contact->getAccountId();
            $this->address = $contact->getHtmlAddressLinesString();
            $this->buyerName = $contact->getName();
        }
        if ($account && $cart) {
            $currency = $account->getAccountData()->getCurrencyCode();
            $this->orderLines = [];
            /** @var CartItem $cartItem */
            foreach ($cart->getItems() as $cartItem) {

                $this->orderLines[] = [
                    "title" => $cartItem->getTitle(),
                    "subtitle" => $cartItem->getSubtitle(),
                    "description" => $cartItem->getDescription(),
                    "quantity" => $cartItem->getQuantity(),
                    "amount" => number_format($cartItem->getUnitPrice($currency, $account->getAccountData()->getTierId()), 2, ".", ""),
                    "currency" => $currency,
                    "subItems" => $cartItem->getSubItems()
                ];
            }
            $this->currency = $currency;
            $this->subtotal = $cart->getTotal();
            $this->taxes = round($this->subtotal * 0.20, 2);
            $this->total = round($this->subtotal + $this->taxes, 2);
        }

        $this->date = date("Y-m-d H:i:s");

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
        return number_format($this->subtotal, 2, ".", "");
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
        return number_format($this->taxes, 2, ".", "");
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
        return number_format($this->total, 2, ".", "");
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

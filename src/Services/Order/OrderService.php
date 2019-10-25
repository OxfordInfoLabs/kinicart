<?php


namespace Kinicart\Services\Order;


use Kiniauth\Objects\Account\Contact;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Account\AccountData;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Order\Order;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinicart\Services\Cart\SessionCart;
use Kinikit\Core\Logging\Logger;

class OrderService {

    private $sessionCart;

    /**
     * OrderService constructor.
     * @param SessionCart $sessionCart
     */
    public function __construct($sessionCart) {
        $this->sessionCart = $sessionCart;
    }


    /**
     * @param $contactId
     * @param $paymentMethodId
     * @param Cart $cart
     */
    public function processOrder($contactId, $paymentMethodId, $cart = null) {
        if (!$cart) {
            $cart = $this->sessionCart->get();
        }
        /** @var Contact $contact */
        $contact = Contact::fetch($contactId);
        /** @var Account $account */
        $account = Account::fetch($contact->getAccountId());
        $currency = $account->getAccountData()->getCurrencyCode();

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = PaymentMethod::fetch($paymentMethodId);
        try {
            $paymentData = $paymentMethod->getPayment()->charge($cart->getTotal(), $currency);
        } catch (\Exception $e) {
            $paymentData = ["error" => $e];
        }

        // Process each cart item.
        foreach ($cart->getItems() as $cartItem) {
            $cartItem->processAll();
        }

        $order = new Order($contact, $cart, $paymentData, $account->getAccountData()->getCurrencyCode());

        $order->save();

        return $order;
    }

    public function getOrders($searchTerm = "", $startDate = null, $endDate = null, $accountId = Account::LOGGED_IN_ACCOUNT) {
        $query = "WHERE account_id = ?";

        if ($searchTerm) {
            $query .= " AND (buyer_name LIKE '%$searchTerm%' OR address LIKE '%$searchTerm%' OR status LIKE '%$searchTerm%' OR total LIKE '%$searchTerm%')";
        }

        if ($startDate) {
            $query .= " AND date >= '$startDate'";
        }

        if ($endDate) {
            $query .= " AND date <= '$endDate'";
        }

        return Order::filter($query, $accountId);
    }

}

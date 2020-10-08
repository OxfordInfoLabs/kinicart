<?php


namespace Kinicart\Services\Order;


use Kiniauth\Objects\Account\Contact;
use Kiniauth\Objects\Communication\Email\AccountTemplatedEmail;
use Kiniauth\Services\Communication\Email\EmailService;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Cart\ProductCartItem;
use Kinicart\Objects\Order\Order;
use Kinicart\Objects\Payment\PaymentMethod;
use Kinicart\Services\Application\SessionData;
use Kinicart\Services\Cart\SessionCart;
use Kinicart\Services\Product\ProductService;
use Kiniauth\Services\Application\Session;


class OrderService {

    // Product service
    private $productService;
    /**
     * @var EmailService
     */
    private $emailService;

    private $sessionCart;

    /**
     * @var Session
     */
    private $session;

    /**
     * OrderService constructor.
     * @param SessionCart $sessionCart
     * @param ProductService $productService
     * @param EmailService $emailService
     * @param Session $session
     */
    public function __construct($sessionCart, $productService, $emailService) {
        $this->sessionCart = $sessionCart;
        $this->productService = $productService;
        $this->emailService = $emailService;
        $this->session = $sessionCart->getSession();

    }

    /**
     * Return an order by its ID
     *
     * @http GET /$id
     *
     * @param $id
     * @return mixed
     */
    public function getOrder($id) {
        return Order::fetch($id);
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


        if ($cart->getTotal() > 0) {
            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = PaymentMethod::fetch($paymentMethodId);
            try {
                $paymentData = $paymentMethod->getPayment()->charge($cart->getTotal(), $currency);
            } catch (\Exception $e) {
                $paymentData = ["error" => $e];
            }
        } else {
            $paymentData = [];
        }

        // Process each cart item.
        foreach ($cart->getItems() as $cartItem) {
            if ($cartItem instanceof ProductCartItem) {
                $this->productService->processProductCartItem($account, $cartItem);
            }
        }

        $order = new Order($contact, $cart, $paymentData, $account);
        $order->save();

        $this->emailService->send(new AccountTemplatedEmail($contact->getAccountId(), "checkout/order-summary", ["order" => $order]), null);

        // The order has now been processed - clear the cart
        $this->sessionCart->clear();

        // Stash the last order for confirmation purposes
        $this->session->setValue(SessionData::LAST_SESSION_ORDER_NAME, $order);

        return $order->getId();
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

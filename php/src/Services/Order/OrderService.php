<?php


namespace Kinicart\Services\Order;


use Kiniauth\Objects\Account\Contact;
use Kiniauth\Objects\Communication\Email\AccountTemplatedEmail;
use Kiniauth\Services\Communication\Email\EmailService;
use Kinicart\Exception\Payment\InvalidBillingContactException;
use Kinicart\Exception\Payment\InvalidPaymentMethodException;
use Kinicart\Exception\Payment\MissingPaymentMethodException;
use Kinicart\Exception\Payment\PaymentFailureException;
use Kinicart\Objects\Account\Account;
use Kinicart\Objects\Cart\Cart;
use Kinicart\Objects\Order\Order;
use Kinicart\Services\Payment\PaymentProvider;
use Kinicart\Services\Application\SessionData;
use Kinicart\Services\Cart\SessionCart;
use Kiniauth\Services\Application\Session;
use Kinicart\ValueObjects\Payment\PaymentResult;
use Kinikit\Core\DependencyInjection\Container;
use Kinikit\Core\DependencyInjection\MissingInterfaceImplementationException;
use Kinikit\Persistence\ORM\Exception\ObjectNotFoundException;


class OrderService {

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
     * @param EmailService $emailService
     * @param Session $session
     */
    public function __construct($sessionCart, $emailService) {
        $this->sessionCart = $sessionCart;
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
     * Get orders with optional filtering
     *
     * @param string $searchTerm
     * @param null $startDate
     * @param null $endDate
     * @param string $accountId
     * @return mixed
     */
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


    /**
     * @param string $paymentProviderKey
     * @param mixed $paymentData
     * @param Cart $cart
     */
    public function processOrder($paymentProviderKey, $paymentData = null, $contactId = null, $cart = null) {


        if (!$cart) {
            $cart = $this->sessionCart->get();
        }


        if ($contactId) {
            try {
                /** @var Contact $contact */
                $contact = Contact::fetch($contactId);
            } catch (ObjectNotFoundException $e) {
                throw new InvalidBillingContactException();
            }
        } else {
            $contact = null;
        }


        /** @var Account $account */
        $account = $cart->getAccountProvider()->provideAccount();


        $currency = $account->getAccountData()->getCurrencyCode();


        if ($cart->getSubTotal() > 0) {

            if ($paymentProviderKey) {

                try {
                    /**
                     * @var PaymentProvider $paymentProvider
                     */
                    $paymentProvider = Container::instance()->getInterfaceImplementation(PaymentProvider::class, $paymentProviderKey);
                } catch (MissingInterfaceImplementationException $e) {
                    throw new InvalidPaymentMethodException();
                }


                try {
                    $paymentResult = $paymentProvider->charge($cart->getTotal(), $currency, $paymentData);
                } catch (\Exception $e) {
                    $paymentResult = new PaymentResult(PaymentResult::STATUS_FAILED, null, $e->getMessage());
                }
            } else {
                throw new MissingPaymentMethodException();
            }
        } else {
            $paymentResult = new PaymentResult(PaymentResult::STATUS_SUCCESS, date("U"));
        }


        // Only process cart completion if successful payment
        if ($paymentResult) {


            // If a failed payment, throw immediately
            if ($paymentResult->getStatus() == PaymentResult::STATUS_FAILED) {
                throw new PaymentFailureException($paymentResult);
            }

            // Process on complete for each cart item
            foreach ($cart->getItems() as $cartItem) {
                $cartItem->onComplete($account);
            }

            $order = new Order($cart, $paymentResult, $account, $contact);
            $order->save();


            $this->emailService->send(new AccountTemplatedEmail($account->getAccountId(), "checkout/order-summary", ["order" => $order]), null);

            // The order has now been processed - clear the cart
            $this->sessionCart->clear();

            // Stash the last order for confirmation purposes
            $this->session->setValue(SessionData::LAST_SESSION_ORDER_NAME, $order);

            return $order->getId();

        }


    }


}

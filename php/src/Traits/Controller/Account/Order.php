<?php


namespace Kinicart\Traits\Controller\Account;


use Kiniauth\Services\Account\ContactService;
use Kinicart\Services\Order\OrderService;

trait Order {

    private $orderService;

    private $contactService;

    /**
     * Order constructor.
     * @param OrderService $orderService
     * @param ContactService $contactService
     */
    public function __construct($orderService, $contactService) {
        $this->orderService = $orderService;
        $this->contactService = $contactService;
    }

    /**
     * Return the orders for the logged in account
     *
     * @http POST /orders
     *
     * @param $payload
     *
     * @return mixed
     */
    public function getOrders($payload = array()) {
        $searchTerm = isset($payload["searchTerm"]) ? $payload["searchTerm"] : null;
        $startDate = isset($payload["startDate"]) ? $payload["startDate"] : null;
        $endDate = isset($payload["endDate"]) ? $payload["endDate"] : null;
        return $this->orderService->getOrders($searchTerm, $startDate, $endDate);
    }

    /**
     * Process the current or supplied cart with the payment method
     *
     * @http GET /process
     *
     * @param $paymentMethodId
     * @param null $contactId
     * @param null $cart
     * @return int
     * @throws \Exception
     */
    public function processOrder($paymentMethodId, $contactId = null, $cart = null) {
        if (!$contactId) {
            $contacts = $this->contactService->getContacts("BILLING");
            $contactId = sizeof($contacts) > 0 ? $contacts[0]->getId(): null;
        }
        
        return $this->orderService->processOrder($contactId, $paymentMethodId, $cart);
    }

    /**
     * Returns an order by ID
     *
     * @http GET /
     *
     * @param $id
     * @return mixed
     */
    public function getOrder($id) {
        return $this->orderService->getOrder($id);
    }

}

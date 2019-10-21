<?php


namespace Kinicart\WebServices\ControllerTraits\Account;


use Kinicart\Services\Order\OrderService;

trait Order {

    private $orderService;

    /**
     * Order constructor.
     * @param OrderService $orderService
     */
    public function __construct($orderService) {
        $this->orderService = $orderService;
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

}

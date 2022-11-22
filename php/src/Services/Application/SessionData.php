<?php

namespace Kinicart\Services\Application;

use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\SecurityService;
use Kinicart\Objects\Order\Order;
use Kinicart\Services\Cart\SessionCart;

/**
 * Subclass the session data class to return extra cart related info.
 *
 * Class SessionData
 * @package Kinicart\Services\Application
 */
class SessionData extends \Kiniauth\Services\Application\SessionData {

    /**
     * Number of items in the session cart
     *
     * @var integer
     */
    private $cartCount;


    /**
     * Completed order if one has been completed this session
     *
     * @var Order
     */
    private $lastSessionOrder;

    const LAST_SESSION_ORDER_NAME = "__kinicart_last_order";


    /**
     * SessionData constructor.
     * @param SecurityService $securityService
     * @param Session $session
     * @param SessionCart $sessionCart
     */
    public function __construct($securityService, $session, $sessionCart) {
        parent::__construct($securityService, $session);
        $this->cartCount = $sessionCart->getNumberOfItems();
        $this->lastSessionOrder = $session->getValue(self::LAST_SESSION_ORDER_NAME);
    }


    /**
     * @return int
     */
    public function getCartCount() {
        return $this->cartCount;
    }


    public function getLastSessionOrder() {
        return $this->lastSessionOrder;
    }


}

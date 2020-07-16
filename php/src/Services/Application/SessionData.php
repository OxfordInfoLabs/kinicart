<?php

namespace Kinicart\Services\Application;

use Kiniauth\Services\Application\Session;
use Kiniauth\Services\Security\SecurityService;
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
     * SessionData constructor.
     * @param SecurityService $securityService
     * @param Session $session
     * @param SessionCart $sessionCart
     */
    public function __construct($securityService, $session, $sessionCart) {
        parent::__construct($securityService, $session);
        $this->cartCount = $sessionCart->getNumberOfItems();
    }


    /**
     * @return int
     */
    public function getCartCount() {
        return $this->cartCount;
    }


}

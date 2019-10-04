<?php

namespace Kinicart\Exception\Cart;

class CartItemDoesNotExistsException extends \Exception {

    public function __construct($itemIndex) {
        parent::__construct("The cart item does not exist at the supplied index");
    }

}

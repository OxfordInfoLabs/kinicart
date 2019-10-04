<?php

namespace Kinicart\Exception\Cart;

use Kinikit\Core\Exception\ItemNotFoundException;

class CartItemDoesNotExistsException extends ItemNotFoundException {

    public function __construct($itemIndex) {
        parent::__construct("The cart item does not exist at index $itemIndex");
    }

}

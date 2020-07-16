<?php


namespace Kinicart\Exception\Product\PackagedProduct;


use Kinikit\Core\Exception\ItemNotFoundException;

class CartItemAddOnDoesNotExistException extends ItemNotFoundException {

    /**
     * Construct with indices into missing item
     *
     * CartItemAddOnDoesNotExistException constructor.
     * @param $addOnIndex
     */
    public function __construct($addOnIndex) {
        parent::__construct("The add on does not exist with index $addOnIndex");
    }

}

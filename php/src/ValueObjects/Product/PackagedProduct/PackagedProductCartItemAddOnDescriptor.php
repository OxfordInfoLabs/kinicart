<?php


namespace Kinicart\ValueObjects\Product\PackagedProduct;


class PackagedProductCartItemAddOnDescriptor {


    /**
     * @var string
     */
    private $addOnIdentifier;


    /**
     * @var int
     */
    private $quantity = 1;

    /**
     * PackagedProductCartItemAddOnDescriptor constructor.
     *
     * @param string $addOnIdentifier
     * @param int $quantity
     */
    public function __construct($addOnIdentifier, $quantity = 1) {
        $this->addOnIdentifier = $addOnIdentifier;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getAddOnIdentifier() {
        return $this->addOnIdentifier;
    }

    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }


}

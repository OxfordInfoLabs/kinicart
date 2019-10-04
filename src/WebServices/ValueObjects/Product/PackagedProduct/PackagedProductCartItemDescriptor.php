<?php

namespace Kinicart\WebServices\ValueObjects\Product\PackagedProduct;

/**
 * Descriptor object used as payload when creating a new packaged product cart item.
 * This encodes the plan and the add ons required for the cart item.
 *
 * Class PackagedProductCartItemDescriptor
 */
class PackagedProductCartItemDescriptor {

    /**
     * @var string
     */
    private $planIdentifier;

    /**
     * @var string[]
     */
    private $addOnIdentifiers = [];

    /**
     * @return string
     */
    public function getPlanIdentifier() {
        return $this->planIdentifier;
    }

    /**
     * @param string $planIdentifier
     */
    public function setPlanIdentifier($planIdentifier) {
        $this->planIdentifier = $planIdentifier;
    }

    /**
     * @return string[]
     */
    public function getAddOnIdentifiers() {
        return $this->addOnIdentifiers;
    }

    /**
     * @param string[] $addOnIdentifiers
     */
    public function setAddOnIdentifiers($addOnIdentifiers) {
        $this->addOnIdentifiers = $addOnIdentifiers;
    }


}


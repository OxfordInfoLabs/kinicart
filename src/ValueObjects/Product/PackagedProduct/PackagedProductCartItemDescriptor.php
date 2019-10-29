<?php

namespace Kinicart\ValueObjects\Product\PackagedProduct;

use Kinicart\Types\Recurrence;

/**
 * Descriptor object used as payload when creating a new packaged product cart item.
 * This encodes the plan and the add ons required for the cart item.
 *
 * Class PackagedProductCartItemDescriptor
 */
class PackagedProductCartItemDescriptor {

    /**
     * The recurrence type for this packaged product - defaults to monthly
     *
     * @var string
     */
    private $recurrenceType = Recurrence::MONTHLY;

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
    public function getRecurrenceType() {
        return $this->recurrenceType;
    }

    /**
     * @param string $recurrenceType
     */
    public function setRecurrenceType($recurrenceType) {
        $this->recurrenceType = $recurrenceType;
    }


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


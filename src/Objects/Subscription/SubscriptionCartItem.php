<?php


namespace Kinicart\Objects\Subscription;


use Kinicart\Objects\Cart\CartItem;


abstract class SubscriptionCartItem extends CartItem {


    /**
     * String product identifier.
     *
     * @var string
     */
    protected $productIdentifier;


    /**
     * The recurrence type for this product instance
     *
     * @var string
     */
    protected $recurrenceType;


    /**
     * The quantity of this recurrence type (defaults to 1)
     *
     * @var integer
     */
    protected $recurrence = 1;


    /**
     * Construct with bits we need for subscription purposes.
     *
     * SubscriptionCartItem constructor.
     */
    public function __construct($productIdentifier, $recurrenceType, $recurrence = 1) {

        $this->productIdentifier = $productIdentifier;
        $this->recurrenceType = $recurrenceType;
        $this->recurrence = $recurrence;

    }

    /**
     * Process
     *
     * @return mixed
     */
    public function process() {

    }
}

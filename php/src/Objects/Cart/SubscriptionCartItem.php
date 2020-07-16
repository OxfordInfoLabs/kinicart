<?php


namespace Kinicart\Objects\Cart;


use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinicart\Objects\Pricing\ProductTierPrice;
use Kinicart\Types\Recurrence;

abstract class SubscriptionCartItem extends ProductCartItem {

    /**
     * The operation on the subscription being performed.
     * One of the constants below. (Defaults to new)
     *
     * @var string
     */
    protected $operation = self::OPERATION_NEW;


    /**
     * The id of the current subscription if the operation is either
     * RENEWAL / ADJUSTMENT
     *
     * @var integer
     */
    protected $subscriptionId;

    /**
     * The recurrence type for this product instance
     *
     * @var string
     */
    protected $recurrenceType = Recurrence::MONTHLY;


    /**
     * The quantity of this recurrence type (defaults to 1)
     *
     * @var integer
     */
    protected $recurrence = 1;


    // Operations on a subscription
    const OPERATION_NEW = "NEW";
    const OPERATION_RENEW = "RENEW";
    const OPERATION_ADJUST = "ADJUST";


    /**
     * Construct with bits we need for subscription purposes.
     *
     * SubscriptionCartItem constructor.
     */
    public function __construct($productIdentifier, $recurrenceType = Recurrence::MONTHLY, $recurrence = 1) {

        parent::__construct($productIdentifier);

        $this->recurrenceType = $recurrenceType;
        $this->recurrence = $recurrence;

    }

    /**
     * @return string
     */
    public function getRecurrenceType() {
        return $this->recurrenceType;
    }

    /**
     * @return int
     */
    public function getRecurrence() {
        return $this->recurrence;
    }

    /**
     * @return string
     */
    public function getOperation() {
        return $this->operation;
    }

    /**
     * @return int
     */
    public function getSubscriptionId() {
        return $this->subscriptionId;
    }


}

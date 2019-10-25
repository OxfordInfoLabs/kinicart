<?php

namespace Kinicart\Objects\Subscription;

use DateTime;
use Kinicart\Objects\Pricing\ProductBasePrice;
use Kinikit\Persistence\ORM\ActiveRecord;

/**
 * Generic Subscription
 *
 * Class Subscription
 *
 * @generate
 * @table kc_subscription
 */
class Subscription extends ActiveRecord {

    /**
     * @var integer
     */
    private $id;


    /**
     * The account for this sub
     *
     * @var integer
     */
    private $accountId;

    /**
     * A helpful textual description for generic listings of subs.
     *
     * @var string
     */
    private $description;


    /**
     * The product identifier for this product
     *
     * @var string
     */
    private $productIdentifier;


    /**
     * An id specific to this product type which is stored when the
     * subscription is created.
     *
     * @var integer
     */
    private $relatedObjectId;


    /**
     * Recurrence type (MONTHLY, ANNUAL)
     *
     * @var string
     */
    private $recurrenceType;


    /**
     * Quantity of recurrence types
     *
     * @var integer
     */
    private $recurrence = 1;


    /**
     * The start date for this subscription
     *
     * @var DateTime
     */
    private $startDate;


    /**
     * The next renewal date for this subscription
     *
     * @var DateTime
     */
    private $nextRenewalDate;


    /**
     * The number of renewals (for fixed term subscriptions).   Set to null
     * for ongoing subs.
     *
     * @var integer
     */
    private $numberOfRenewals;


    /**
     * Construct with required fields
     *
     * Subscription constructor.
     * @param $accountId
     * @param $description
     * @param $productIdentifier
     * @param $relatedObjectId
     * @param string $recurrenceType
     * @param int $recurrence
     * @param null $numberOfRenewals
     */
    public function __construct($accountId, $description, $productIdentifier, $relatedObjectId, $recurrenceType = ProductBasePrice::RECURRENCE_MONTHLY,
                                $recurrence = 1, $numberOfRenewals = null) {

        $this->accountId = $accountId;
        $this->description = $description;
        $this->productIdentifier = $productIdentifier;
        $this->relatedObjectId = $relatedObjectId;
        $this->recurrenceType = $recurrenceType;
        $this->recurrence = $recurrence;
        $this->numberOfRenewals = $numberOfRenewals;

        $this->startDate = new DateTime();

        // Add the correct period to the dates
        $renewalDate = new DateTime();
        $renewalDate->add(new \DateInterval("P" . $recurrence . ($recurrenceType == ProductBasePrice::RECURRENCE_MONTHLY ? "M" : "Y")));
        $this->nextRenewalDate = $renewalDate;

    }


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getAccountId() {
        return $this->accountId;
    }

    /**
     * @param int $accountId
     */
    public function setAccountId($accountId) {
        $this->accountId = $accountId;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getProductIdentifier() {
        return $this->productIdentifier;
    }

    /**
     * @param string $productIdentifier
     */
    public function setProductIdentifier($productIdentifier) {
        $this->productIdentifier = $productIdentifier;
    }

    /**
     * @return int
     */
    public function getRelatedObjectId() {
        return $this->relatedObjectId;
    }

    /**
     * @param int $relatedObjectId
     */
    public function setRelatedObjectId($relatedObjectId) {
        $this->relatedObjectId = $relatedObjectId;
    }

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
     * @return int
     */
    public function getRecurrence() {
        return $this->recurrence;
    }

    /**
     * @param int $recurrence
     */
    public function setRecurrence($recurrence) {
        $this->recurrence = $recurrence;
    }

    /**
     * @return DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @param DateTime $startDate
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    /**
     * @return DateTime
     */
    public function getNextRenewalDate() {
        return $this->nextRenewalDate;
    }

    /**
     * @param DateTime $nextRenewalDate
     */
    public function setNextRenewalDate($nextRenewalDate) {
        $this->nextRenewalDate = $nextRenewalDate;
    }

    /**
     * @return int
     */
    public function getNumberOfRenewals() {
        return $this->numberOfRenewals;
    }

    /**
     * @param int $numberOfRenewals
     */
    public function setNumberOfRenewals($numberOfRenewals) {
        $this->numberOfRenewals = $numberOfRenewals;
    }


}


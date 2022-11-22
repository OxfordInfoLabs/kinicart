<?php

namespace Kinicart\Objects\Pricing;

use Kinikit\Persistence\ORM\ActiveRecord;


/**
 * Pricing tier - these are typically assigned to accounts
 *
 * Class Tier
 * @package Kinicart\Objects\Pricing
 *
 * @table kc_tier
 * @generate
 */
class Tier extends ActiveRecord {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;


    /**
     * @var float
     */
    private $defaultPriceMultiplier;


    /**
     * @var boolean
     */
    private $defaultTier;

    /**
     * @var integer
     */
    private $upgradeOrder;

    /**
     * Tier constructor.
     * @param int $id
     * @param string $name
     * @param float $defaultPriceMultiplier
     * @param bool $defaultTier
     * @param int $upgradeOrder
     */
    public function __construct($name, $defaultPriceMultiplier, $defaultTier, $upgradeOrder, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->defaultPriceMultiplier = $defaultPriceMultiplier;
        $this->defaultTier = $defaultTier;
        $this->upgradeOrder = $upgradeOrder;
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
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getDefaultPriceMultiplier() {
        return $this->defaultPriceMultiplier;
    }

    /**
     * @param float $defaultPriceMultiplier
     */
    public function setDefaultPriceMultiplier($defaultPriceMultiplier) {
        $this->defaultPriceMultiplier = $defaultPriceMultiplier;
    }

    /**
     * @return bool
     */
    public function isDefaultTier() {
        return $this->defaultTier;
    }

    /**
     * @param bool $defaultTier
     */
    public function setDefaultTier($defaultTier) {
        $this->defaultTier = $defaultTier;
    }

    /**
     * @return int
     */
    public function getUpgradeOrder() {
        return $this->upgradeOrder;
    }

    /**
     * @param int $upgradeOrder
     */
    public function setUpgradeOrder($upgradeOrder) {
        $this->upgradeOrder = $upgradeOrder;
    }


}

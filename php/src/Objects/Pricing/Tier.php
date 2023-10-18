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
     * Indicator as to whether this tier is private or not.
     *
     * @var boolean
     */
    private $private;


    /**
     * An array of explicit tier privileges supplied as an array indexed by
     *
     *
     * @var string[]
     * @json
     */
    protected $privileges = array();

    /**
     * Tier constructor.
     * @param string $name
     * @param float $defaultPriceMultiplier
     * @param bool $defaultTier
     * @param int $upgradeOrder
     * @param boolean $private
     * @param int $id
     */
    public function __construct($name, $defaultPriceMultiplier, $defaultTier, $upgradeOrder, $private = false, $id = null) {
        $this->id = $id;
        $this->name = $name;
        $this->defaultPriceMultiplier = $defaultPriceMultiplier;
        $this->defaultTier = $defaultTier;
        $this->upgradeOrder = $upgradeOrder;
        $this->private = $private;
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

    /**
     * @return boolean
     */
    public function isPrivate() {
        return $this->private;
    }

    /**
     * @param boolean $private
     */
    public function setPrivate($private) {
        $this->private = $private;
    }

    /**
     * @return string[]
     */
    public function getPrivileges() {
        return $this->privileges;
    }

    /**
     * @param string[] $privileges
     */
    public function setPrivileges($privileges) {
        $this->privileges = $privileges;
    }


}

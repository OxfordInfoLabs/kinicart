<?php

namespace Kinicart\Objects\Product\PackagedProduct;

/**
 * Features are used to build packages for products.
 *
 *
 * Class Feature
 */
class Feature {

    /**
     * Unique string identifier for this feature within
     * the context of this package
     *
     * @var string
     */
    private $identifier;

    /**
     * The main title for a feature.
     *
     * @var string
     */
    private $title;

    /**
     * A more detailed description for a feature
     *
     * @var string
     */
    private $description;

    /**
     * Type of feature - currently either PACKAGE (for inclusion in packages)
     * or EXCESS (for excess charging out of plan).
     *
     * @var string
     */
    private $type;


    const TYPE_PACKAGE = "PACKAGE";
    const TYPE_EXCESS = "EXCESS";

    /**
     * Brand new feature constructor
     *
     * Feature constructor.
     * @param string $identifier
     * @param string $title
     * @param string $description
     */
    public function __construct($identifier, $title, $description, $type = self::TYPE_PACKAGE) {
        $this->identifier = $identifier;
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }


}

<?php


namespace Kinicart\Objects\Account;

use Kinikit\Core\Object\SerialisableObject;


/**
 * Privilege class which encodes a simple key and description for a system privilege.
 */
class Privilege extends SerialisableObject {

    /**
     * A string identifier for a privilege for programmatic use.
     *
     * @var string
     * @validation required
     */
    private $key;


    /**
     * A full description for this privilege.
     *
     * @var string
     * @validation required
     */
    private $description;


    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key) {
        $this->key = $key;
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


}

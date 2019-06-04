<?php


namespace Kinicart\Objects\Application;

use Kinikit\Core\Util\ArrayUtils;
use Kinikit\MVC\Framework\SourceBaseManager;
use Kinikit\Persistence\UPF\Object\ActiveRecord;

/**
 * Setting object for persisting settings.
 *
 * Class Setting
 *
 * @ormTable kc_setting
 * @interceptors \Kinicart\Objects\Application\SettingInterceptor
 */
class Setting extends ActiveRecord {

    private static $settingDefinitions;

    /**
     * @var integer
     * @primaryKey
     */
    private $accountId;

    /**
     * @var string
     * @primaryKey
     */
    private $key;

    /**
     * @var string
     */
    private $value;


    /**
     * @var integer
     * @primaryKey
     */
    private $valueIndex = 0;


    /**
     * Non-persisted definition field
     *
     * @unmapped
     * @var string
     */
    protected $title;

    /**
     * Non-persisted definition field
     *
     * @unmapped
     * @var string
     */
    protected $description;


    /**
     * Non-persisted definition field
     *
     * @unmapped
     * @var string
     */
    protected $type;


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
    public function getValue() {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValueIndex() {
        return $this->valueIndex;
    }

    /**
     * @param int $valueIndex
     */
    public function setValueIndex($valueIndex) {
        $this->valueIndex = $valueIndex;
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


    /**
     * Populate this setting with a definition.
     *
     * @param $setting Setting
     */
    public function populateSettingWithDefinition() {
        $defs = self::getSettingDefinitions();
        if (isset($defs[$this->getKey()])) {
            $def = $defs[$this->getKey()];
            unset($def["scope"]);
            $this->bind($def);
        }
    }


    /**
     * Get all setting definitions.  Cached for performance.
     */
    public static function getSettingDefinitions() {
        if (!self::$settingDefinitions) {
            $settingDefinitions = array();
            foreach (SourceBaseManager::instance()->getSourceBases() as $sourceBase) {
                if (file_exists($sourceBase . "/Config/settings.json")) {
                    $newDefs = json_decode(file_get_contents($sourceBase . "/Config/settings.json"), true);
                    $settingDefinitions = array_merge($settingDefinitions, $newDefs);
                }
            }

            $indexedDefs = array();
            foreach ($settingDefinitions as $definition) {
                $indexedDefs[$definition["key"]] = $definition;
            }

            self::$settingDefinitions = $indexedDefs;
        }

        return self::$settingDefinitions;
    }




}

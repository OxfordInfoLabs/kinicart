<?php


namespace Kinicart\Services\Application;


use Kinicart\Objects\Application\Setting;

class SettingsService {


    /**
     * Get a setting by key and value
     *
     * @param $key string
     * @param $value string
     */
    public function getSettingByKeyAndValue($key, $value) {
        $matches = Setting::query("WHERE key = ? AND value = ?", $key, $value);
        if (sizeof($matches) > 0) {
            return $matches[0];
        } else {
            return null;
        }
    }

}

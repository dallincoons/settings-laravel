<?php

namespace SMST\SettingsLaravel\Tests\Fixtures\Repositories;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use SMST\Settings\Repository\SettingsRepository;
use SMST\Settings\Setting;

class FakeSettingsRepository implements SettingsRepository
{
    protected $settings = [];

    /**
     * @param string $code
     * @return Option
     */
    public function findByKey(string $code): Option
    {
        if (isset($this->settings[$code])) {
            return Some::create($this->settings[$code]);
        }

        return None::create();
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->settings;
    }

    /**
     * @param Setting $setting
     */
    public function add(Setting $setting)
    {
        $this->settings[$setting->key()] = $setting->value();
    }

    /**
     * @param Setting $setting
     */
    public function update(Setting $setting)
    {
        $this->settings[$setting->key()] = $setting->value();
    }

    /**
     * @param Setting $setting
     */
    public function remove(Setting $setting)
    {
        $this->removeByKey($setting->key());
    }

    /**
     * @param string $key
     */
    public function removeByKey(string $key)
    {
        unset($this->settings[$key]);
    }
}

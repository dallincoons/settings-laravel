<?php

namespace SMST\SettingsLaravel\Services;

use SMST\Settings\Repository\SettingsRepository;
use SMST\Settings\Setting;
use WSNYC\ClassFinder\ClassFinder;

class SettingsSyncer
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function settings(): array
    {
        return collect(array_wrap(config('settings.paths')))
            ->flatMap(function ($path) {
                return ClassFinder::findClasses($path);
            })
            ->all();
    }

    /**
     * @param array $settings
     * @return array
     */
    public function attachSettings(array $settings): array
    {
        return collect($settings)
            ->map(function ($settingClass) {
                return new $settingClass();
            })
            ->filter(function (Setting $setting) {
                return $this->repository->findByKey($setting->key())->isEmpty();
            })
            ->map(function (Setting $setting) {
                $this->repository->add($setting);
                return $setting->key();
            })
            ->all();
    }

    /**
     * @param array $settings
     * @return array
     */
    public function detachSettings(array $settingNames): array
    {
        return collect($this->repository->all())
            ->filter(function ($value, $key) use ($settingNames) {
                return !in_array(studly_case($key), array_map('class_basename', $settingNames));
            })
            ->map(function ($value, $key) {
                $this->repository->removeByKey($key);
                return $key;
            })
            ->values()
            ->all();
    }
}

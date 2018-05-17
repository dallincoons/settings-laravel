<?php

namespace SMST\SettingsLaravel;

use Illuminate\Support\Arr;
use SMST\Settings\Repository\SettingsRepository;
use SMST\SettingsLaravel\Exceptions\SettingNotFoundException;
use WSNYC\ClassFinder\ClassFinder;

class Settings
{
    /**
     * @var SettingsRepository
     */
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function update(string $key, $value)
    {
        $class = $this->findSettingClass($key);

        $setting = new $class($value);

        $this->repository->update($setting);
    }

    /**
     * @param string $key
     * @return mixed|string
     * @throws SettingNotFoundException
     */
    private function findSettingClass(string $key)
    {
        $class = Arr::mapFirst($this->settingsPaths(), function ($path) use ($key) {
            $pattern = studly_case($key) . '.php';
            return $this->firstClassWithPattern($path, $pattern);
        });

        if (empty($class)) {
            throw new SettingNotFoundException('Setting not found with key: ' . $key);
        }

        return $class;
    }

    /**
     * @return array
     */
    private function settingsPaths(): array
    {
        return array_wrap(config('settings.paths'));
    }

    /**
     * @param string $path
     * @param string $pattern
     * @return string
     */
    private function firstClassWithPattern(string $path, string $pattern): string
    {
        return (string) array_first(ClassFinder::findClasses($path, '*' . $pattern));
    }
}

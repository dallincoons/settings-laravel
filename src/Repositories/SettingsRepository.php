<?php

namespace SMST\SettingsLaravel\Repositories;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use Ramsey\Uuid\Uuid;
use SMST\Settings\Setting;
use SMST\Settings\Repository\SettingsRepository as BaseRepository;

class SettingsRepository implements BaseRepository
{
    /**
     * @param Setting $setting
     * @return bool
     */
    public function add(Setting $setting): bool
    {
        //unique constraint is on key
        return $this->builder()->insert([
            'id'    => Uuid::uuid4(),
            'key'   => $setting->key(),
            'value' => $setting->value(),
        ]);
    }

    /**
     * @param string $key
     * @return Option
     */
    public function findByKey(string $key): Option
    {
        $record = $this->builder()->where([
            'key' => $key,
        ])->first();

        if (count($record)) {
            return Some::create($record);
        }

        return None::create();
    }

    /**
     * @param Setting $setting
     * @return bool
     */
    public function update(Setting $setting): bool
    {
        return $this->builder()->update([
            'key'   => $setting->key(),
            'value' => $setting->value(),
        ]);
    }

    /**
     * @param Setting $setting
     * @return int
     */
    public function remove(Setting $setting): int
    {
        return $this->removeByKey($setting->key());
    }

    /**
     * @param string $key
     * @return int
     */
    public function removeByKey(string $key): int
    {
        return $this->builder()->where([
            'key' => $key,
        ])->delete();
    }

    /**
     * @return mixed
     */
    private function builder()
    {
        return \DB::table(config('settings.table'));
    }
}

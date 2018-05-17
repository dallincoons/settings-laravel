<?php

namespace SMST\SettingsLaravel\Tests\Fixtures\Repositories\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOption\None;
use SMST\SettingsLaravel\Repositories\SettingsRepository;
use SMST\SettingsLaravel\Tests\Fixtures\ExampleSetting;
use SMST\SettingsLaravel\Tests\TestCase;

/**
 * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::<private>
 *
 * @group repository-tests
 */
class SettingsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var SettingsRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = app(SettingsRepository::class);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::add
     *
     * @test
     */
    public function it_adds_setting()
    {
        $setting = new ExampleSetting;

        $this->repository->add($setting);

        $this->assertDatabaseHas('settings', [
            'key' => $setting->key(),
            'value' => $setting->value()
        ]);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::add
     *
     * @test
     */
    public function it_cannot_add_settings_with_duplicate_key()
    {
        $setting = new ExampleSetting;

        $this->repository->add($setting);

        $this->assertException(function () use ($setting) {
            $this->repository->add($setting);
        }, function (\Throwable $e) {
            $this->assertContains('unique constraint', strtolower($e->getMessage()));
        });
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::findByKey
     *
     * @test
     */
    public function it_finds_setting_by_key()
    {
        $setting = new ExampleSetting('test123');

        $this->repository->add($setting);

        $setting = $this->repository->findByKey($setting->key());

        $this->assertEquals('test123', $setting->get()->value);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::findByKey
     *
     * @test
     */
    public function it_handles_finding_no_settings()
    {
        $setting = $this->repository->findByKey('non existent');

        $this->assertInstanceOf(None::class, $setting);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::update
     *
     * @test
     */
    public function it_updates_setting()
    {
        $setting = new ExampleSetting('test123');

        $this->repository->add($setting);

        $newSetting = new ExampleSetting('test456');

        $this->repository->update($newSetting);

        $this->assertDatabaseHas('settings', [
            'key' => $newSetting->key(),
            'value' => $newSetting->value()
        ]);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::remove
     *
     * @test
     */
    public function it_removes_setting()
    {
        $setting = new ExampleSetting('test123');

        $this->repository->add($setting);

        $this->repository->remove($setting);

        $this->assertDatabaseMissing('settings', [
            'key' => $setting->key(),
        ]);
    }

    /**
     * @covers \SMST\SettingsLaravel\Repositories\SettingsRepository::removeByKey
     *
     * @test
     */
    public function it_removes_setting_by_key()
    {
        $setting = new ExampleSetting('test123');

        $this->repository->add($setting);

        $this->repository->removeByKey($setting->key());

        $this->assertDatabaseMissing('settings', [
            'key' => $setting->key(),
        ]);
    }
}

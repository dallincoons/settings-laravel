<?php

namespace SMST\SettingsLaravel\Tests\Console\Commands;

use SMST\Settings\Repository\SettingsRepository;
use SMST\SettingsLaravel\Services\SettingsSyncer;
use SMST\SettingsLaravel\Tests\Fixtures\Repositories\FakeSettingsRepository;
use SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir\SettingOne;
use SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir\SettingTwo;
use SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir2\SettingFourth;
use SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir2\SettingThird;
use SMST\SettingsLaravel\Tests\TestCase;

/**
 * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::__construct
 *
 * @group service-tests
 * @group settings-sync-tests
 */
class SettingSyncerTest extends TestCase
{
    /**
     * @var SettingsRepository
     */
    private $repository;
    /**
     * @var SettingsSyncer
     */
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new FakeSettingsRepository();

        $this->service = new SettingsSyncer($this->repository);
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::settings
     *
     * @test
     */
    public function it_gets_list_of_setting_classes()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
            base_path('../tests/Fixtures/TestSettingDir2'),
        ]);

        $setting = $this->service->settings();

        $this->assertEquals([
            SettingOne::class,
            SettingTwo::class,
            SettingFourth::class,
            SettingThird::class,
        ], $setting);
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::attachSettings
     *
     * @test
     */
    public function it_adds_multiple_settings()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(2, $this->repository->all());
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::attachSettings
     *
     * @test
     */
    public function it_returns_list_of_added_settings()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $settings = $this->service->attachSettings($settings);

        $this->assertEquals([
            SettingOne::KEY,
            SettingTwo::KEY,
        ], $settings);
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::attachSettings
     *
     * @test
     */
    public function adding_settings_is_accumulative()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(2, $this->repository->all());

        $settings = [
            SettingThird::class,
            SettingFourth::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(4, $this->repository->all());
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::attachSettings
     *
     * @test
     */
    public function it_does_not_update_previously_created_settings()
    {
        $settings = [
            SettingOne::class,
        ];

        $this->service->attachSettings($settings);

        $settings = [
            \SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir3\SettingOne::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertEquals(SettingOne::VALUE, $this->repository->findByKey(SettingOne::KEY)->get());
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::detachSettings
     *
     * @test
     */
    public function it_removes_previously_added_settings()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(2, $this->repository->all());

        $this->service->detachSettings([SettingTwo::class]);

        $this->assertCount(1, $this->repository->all());
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::detachSettings()
     *
     * @test
     */
    public function it_returns_list_of_removed_settings()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(2, $this->repository->all());

        $result = $this->service->detachSettings([SettingOne::class]);

        $this->assertEquals([SettingTwo::KEY], $result);
    }

    /**
     * @covers \SMST\SettingsLaravel\Services\SettingsSyncer::detachSettings
     *
     * @test
     */
    public function it_does_not_blow_up_if_setting_is_deleted_twice()
    {
        $settings = [
            SettingOne::class,
            SettingTwo::class,
        ];

        $this->service->attachSettings($settings);

        $this->assertCount(2, $this->repository->all());

        $this->service->detachSettings([SettingOne::class]);

        $this->assertCount(1, $this->repository->all());

        $this->service->detachSettings([SettingOne::class]);

        $this->assertCount(1, $this->repository->all());
    }
}

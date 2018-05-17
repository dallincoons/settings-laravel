<?php

namespace SMST\SettingsLaravel\Tests\Feature\Console\Commands;

use SMST\Settings\Repository\SettingsRepository;
use SMST\SettingsLaravel\Tests\Fixtures\Repositories\FakeSettingsRepository;
use SMST\SettingsLaravel\Tests\TestCase;

/**
 * @covers \SMST\SettingsLaravel\Console\Commands\SettingsSync::handle
 * @covers \SMST\SettingsLaravel\ConsoleServiceProvider::register
 *
 * @group command-tests
 * @group settings-sync-tests
 */
class SettingsSyncTest extends TestCase
{
    /**
     * @var FakeSettingsRepository
     */
    private $repository;

    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->app->make(SettingsRepository::class);
    }

    /** @test */
    public function it_adds_all_settings_in_multiple_directories()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
            base_path('../tests/Fixtures/TestSettingDir2'),
        ]);

        \Artisan::call('settings:sync');

        $this->assertCount(4, $this->repository->all());
    }

    /** @test */
    public function it_adds_new_settings_classes_in_directory()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir')
        ]);

        \Artisan::call('settings:sync');

        $this->assertCount(2, $this->repository->all());

        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
            base_path('../tests/Fixtures/TestSettingDir2')
        ]);

        \Artisan::call('settings:sync');

        $this->assertCount(4, $this->repository->all());
    }

    /** @test */
    public function it_delete_records_than_no_longer_have_associated_class()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
            base_path('../tests/Fixtures/TestSettingDir2'),
        ]);

        \Artisan::call('settings:sync');

        $this->assertCount(4, $this->repository->all());

        $this->setConfig([base_path('../tests/Fixtures/TestSettingDir')]);

        \Artisan::call('settings:sync');

        $this->assertCount(2, $this->repository->all());
    }

    /** @test */
    public function it_informs_user_of_added_settings()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir')
        ]);

        \Artisan::call('settings:sync');

        $output = \Artisan::output();
        $this->assertContains('Setting added: setting_one', $output);
        $this->assertContains('Setting added: setting_two', $output);
    }

    /** @test */
    public function it_informs_user_of_removed_settings()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
            base_path('../tests/Fixtures/TestSettingDir2'),
        ]);

        \Artisan::call('settings:sync');

        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
        ]);

        \Artisan::call('settings:sync');

        $output = \Artisan::output();
        $this->assertContains('Setting removed: setting_third', $output);
        $this->assertContains('Setting removed: setting_fourth', $output);
    }
}

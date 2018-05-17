<?php

namespace SMST\SettingsLaravel\Tests\Unit;

use SMST\SettingsLaravel\Exceptions\SettingNotFoundException;
use SMST\SettingsLaravel\Settings;
use SMST\SettingsLaravel\Tests\Fixtures\Repositories\FakeSettingsRepository;
use SMST\SettingsLaravel\Tests\Fixtures\TestSettingDir\SettingOne;
use SMST\SettingsLaravel\Tests\TestCase;

/**
 * @covers \SMST\SettingsLaravel\Settings::<private>
 * @covers \SMST\SettingsLaravel\Settings::__construct
 *
 * @group service-tests
 * @group settings-tests
 */
class SettingsTest extends TestCase
{
    /**
     * @var FakeSettingsRepository
     */
    private $repository;
    /**
     * @var Settings
     */
    private $service;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new FakeSettingsRepository();

        $this->service = new Settings($this->repository);
    }

    /**
     * @covers \SMST\SettingsLaravel\Settings::update
     *
     * @test
     */
    public function it_updates_setting()
    {
        $this->setConfig([
            base_path('../tests/Fixtures/TestSettingDir'),
        ]);

        $this->repository->add(new SettingOne);

        $this->assertEquals(SettingOne::VALUE, $this->repository->findByKey(SettingOne::KEY)->get());

        $this->service->update(SettingOne::KEY, 'test_updated');

        $this->assertEquals('test_updated', $this->repository->findByKey(SettingOne::KEY)->get());
    }

    /**
     * @covers \SMST\SettingsLaravel\Settings::update
     *
     * @test
     */
    public function it_throw_exception_if_no_class_is_found_directory()
    {
        $this->assertExceptionInstanceOf(function () {
            $this->service->update('garbage', 'garbage_value');
        }, SettingNotFoundException::class);
    }
}

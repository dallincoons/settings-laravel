<?php

namespace SMST\SettingsLaravel\Tests\Macros;

use Illuminate\Support\Arr;
use SMST\SettingsLaravel\Tests\TestCase;

class ArrMacrosTest extends TestCase
{
    /**
     * @covers \SMST\SettingsLaravel\MacroServiceProvider::register
     *
     * @test
     */
    public function macros_are_registered()
    {
        $this->assertTrue(Arr::hasMacro('mapFirst'));
    }

    /**
     * @covers \SMST\SettingsLaravel\Macros\ArrMacros::mapFirst
     *
     * @test
     */
    public function it_uses_first_value_to_pass_test_and_returns_callback_result()
    {
        $items = [
            'test1',
            'test2',
            'test3'
        ];

        $result = Arr::mapFirst($items, function ($item) {
            if ($item == 'test2') {
                return $item . '345';
            }
        });

        $this->assertEquals('test2345', $result);
    }

    /**
     * @covers \SMST\SettingsLaravel\Macros\ArrMacros::mapFirst
     *
     * @test
     */
    public function returns_default_when_there_are_no_matches()
    {
        $items = [
            'test1',
            'test2',
        ];

        $result = Arr::mapFirst($items, function ($item) {});

        $this->assertEquals(null, $result);

        $result = Arr::mapFirst($items, function ($item) {}, 'default');

        $this->assertEquals('default', $result);
    }
}

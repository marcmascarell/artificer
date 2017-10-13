<?php

namespace Mascame\Artificer\Tests\Unit;

use Mascame\Artificer\Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_config_is_properly_loaded()
    {
        $this->assertNotEmpty(config('admin'));
        $this->assertNotEmpty(config('admin.title'));
        $this->assertNotEmpty(config('admin.auth'));
        $this->assertNotEmpty(config('admin.models.ArtificerModelExample'));
    }
}

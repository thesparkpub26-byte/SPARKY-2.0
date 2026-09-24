<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_run_and_home_loads(): void
    {
        $this->assertSame('sparky_test', config('database.connections.mysql.database'));
        $this->get('/')->assertOk();
    }
}

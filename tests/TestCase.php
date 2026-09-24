<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // RefreshDatabase wipes every table. Refuse to run unless this is clearly the throwaway test database.
        $database = (string) config('database.connections.' . config('database.default') . '.database');
        if (!str_ends_with($database, '_test')) {
            $this->fail("Refusing to run tests against '{$database}': only databases ending in _test are allowed.");
        }
    }
}

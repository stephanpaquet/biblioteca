<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure we're using the testing environment
        $this->app['env'] = 'testing';

        // For SQLite testing, configure pragmas for better performance
        if (config('database.default') === 'sqlite_testing') {
            DB::statement('PRAGMA foreign_keys=ON');
            DB::statement('PRAGMA synchronous=OFF');
            DB::statement('PRAGMA journal_mode=MEMORY');
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Refresh a conventional test database.
     */
    protected function refreshTestDatabase(): void
    {
        if (! RefreshDatabase::$migrated) {
            $this->artisan('migrate:fresh', [
                '--drop-views' => $this->shouldDropViews(),
                '--drop-types' => $this->shouldDropTypes(),
            ]);

            RefreshDatabase::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}

<?php

namespace Database\Seeders;

use Devdojo\Foundation\Models\FoundationSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Sync Foundation feature flags from the config defaults.
        foreach (config('foundation.features', []) as $feature => $enabled) {
            FoundationSetting::updateOrCreate(
                ['key' => 'features.'.$feature],
                ['value' => $enabled ? '1' : '0'],
            );
        }

        $this->call([
            PlanSeeder::class,
            DemoSeeder::class,
        ]);
    }
}

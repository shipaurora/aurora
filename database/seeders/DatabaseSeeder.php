<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * The website's own content, after php artisan aurora:install has set up the CMS.
     */
    public function run(): void
    {
        $this->call(HomepageSeeder::class);
    }
}

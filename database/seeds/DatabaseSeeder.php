<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EmploymentTypesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(DashboardComponentTableSeeder::class);
        $this->call(ScreenshotSettingTableSeeder::class);
        $this->call(MailSetupTableSeeder::class);

    }
}

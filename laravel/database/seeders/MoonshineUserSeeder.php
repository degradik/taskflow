<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use MoonShine\Models\MoonshineUser;
use PHPUnit\Logging\TeamCity\TestSuiteBeforeFirstTestMethodErroredSubscriber;

class MoonshineUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MoonshineUser::query()->create([
            "name" => "admin",
            "password" => bcrypt("123123"),
            "email" => "admin"
        ]);
    }
}

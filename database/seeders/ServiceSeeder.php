<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => "PS3",
            'price' => "30000",
        ]);
        Service::create([
            'name' => "PS4",
            'price' => "40000",
        ]);
    }
}

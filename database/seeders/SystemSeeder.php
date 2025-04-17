<?php

namespace Database\Seeders;

use App\Models\System;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a system
        $system = System::create([
            'name' => 'Test System',
            'system_id' => Str::uuid(),
            'location' => 'Test Location',
            'user_id' => 2, // Or replace with a valid user ID
            'tracking_system_working' => true,
            'water_level' => 50,
            'next_clean' => now()->addDays(3),
            'next_clean_after' => 4320,
            'cleaning' => false,
            'temperature' => 25,
        ]);

        // Attach a cell
        $cell = $system->cells()->create([
            'name' => 'Test Cell',
            'cell_id' => Str::uuid(),
            'current' => rand(10, 30),
            'voltage' => rand(200, 250),
            'power' => rand(1000, 1500),
        ]);

        // Add 24 energy records (hourly)
        $baseTime = Carbon::today();
        for ($i = 0; $i < 24; $i++) {
            $cell->energies()->create([
                'energy' => rand(10, 100),
                'created_at' => $baseTime->copy()->addHours($i),
                'updated_at' => $baseTime->copy()->addHours($i),
            ]);
        }

        // Add random faults (between 1 and 5 records)
        $faultCount = rand(1, 10);
        for ($i = 0; $i < $faultCount; $i++) {
            $cell->faults()->create([
                'value' => rand(0, 7),
                'created_at' => now()->subHours(rand(1, 72)),
            ]);
        }
    }
}

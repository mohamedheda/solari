<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::query()->create([
            'name' => 'Manager',
            'email' => 'manger@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' =>fake()->imageUrl(word: 'Manager')
        ]);
        $manager->addRole('manager');
        $technician = User::query()->create([
            'name' => 'Technician',
            'parent_id' => $manager->id ,
            'email' => 'technician@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Technician')
        ]);
        $technician->addRole('technician');
        $client = User::query()->create([
            'name' => 'Client',
            'parent_id' => $manager->id ,
            'email' => 'client@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Client')
        ]);
        $client->addRole('client');
        $manager = User::query()->create([
            'name' => 'Manager',
            'email' => 'manger1@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' =>fake()->imageUrl(word: 'Manager')
        ]);
        $manager->addRole('manager');
        $technician = User::query()->create([
            'name' => 'Technician',
            'parent_id' => $manager->id ,
            'email' => 'technician1@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Technician')
        ]);
        $technician->addRole('technician');
        $client = User::query()->create([
            'name' => 'Client',
            'parent_id' => $manager->id ,
            'email' => 'client1@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Client')
        ]);
        $client->addRole('client');
        $manager = User::query()->create([
            'name' => 'Manager',
            'parent_id' => $manager->id ,
            'email' => 'manger2@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' =>fake()->imageUrl(word: 'Manager')
        ]);
        $manager->addRole('manager');
        $technician = User::query()->create([
            'name' => 'Technician',
            'parent_id' => $manager->id ,
            'email' => 'technician2@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Technician')
        ]);
        $technician->addRole('technician');
        $client = User::query()->create([
            'name' => 'Client',
            'parent_id' => $manager->id ,
            'email' => 'client2@solari.com',
            'password' => 'solari2025',
            'phone' => fake()->phoneNumber,
            'image' => fake()->imageUrl(word: 'Client')
        ]);
        $client->addRole('client');
    }
}

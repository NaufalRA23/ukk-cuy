<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // $this->call(RoleSeeder::class);
        // $this->call(MemberSeeder::class);
        $this->call(ProdukSeeder::class);

        // User::factory()->create([
        //     'name' => 'Test Kasir',
        //     'email' => 'kasir@gmail.com',
        //     'role_id' => 4,
        //     'password' => 'password',
        // ]);
    }
}

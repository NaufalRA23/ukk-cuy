<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'user_id' => 1, // Assuming user_id 1 is for Admin User
                'gender' => 'male',
                'alamat' => 'Admin alamat',
            ],
            [
                'user_id' => 4, // Assuming user_id 2 is for Pimpinan User
                'gender' => 'female',
                'alamat' => 'Pimpinan alamat',
            ],
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}

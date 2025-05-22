<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'id'             => 999,
            'first_name'     => 'Admin',
            'last_name'      => 'Admin',
            'email'          => 'keochamraeun54@gmail.com',
            'password'       => bcrypt('123456'),
            'zip'            => '12345',
            'city'           => 'Phnum Penh',
            'state'          => 'Phnum Penh',
            'country'        => 'Morocco',
            'address'        => 'keochamraeun54@gmail.com',
            'phone'          => '0886576689',
            'status'         => 1,
            'role_id'        => 1,
            'remember_token' => null,
            'created_at'     => now(),
        ]);
    }
}

<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone_no' => '1234567890',
            'usertype' => 1,
            'status' => 1,
            'password' => Hash::make('123456'),
        ]);

    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@e-cart.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('eT#</!9UR&fPQ12s'),
                'role' => 1,
            ]
        );
    }
}

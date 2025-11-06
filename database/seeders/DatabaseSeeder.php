<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**.
     * php artisan db:seed --class=DatabaseSeeder
     */
    public function run()
    {
        $this->call([
            ProvidersTableSeeder::class,
            ServicesTableSeeder::class,
        ]);

        // Crear usuario admin (evita duplicados)
        User::firstOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );
    }
}

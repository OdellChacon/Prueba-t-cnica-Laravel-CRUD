<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
	public function run()
	{
		$email = 'admin@example.test';

		$user = User::updateOrCreate(
			['email' => $email],
			[
				'name' => 'admin',
				'email_verified_at' => now(),
				'password' => Hash::make('admin'),
				'remember_token' => Str::random(10),
			]
		);

		$this->command->info("Admin user created/updated: {$user->email} (password: admin)");
	}
}

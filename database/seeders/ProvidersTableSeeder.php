<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProvidersTableSeeder extends Seeder
{
	/**
	 *Ejecutar: php artisan db:seed --class=DatabaseSeeder
	 */
	public function run()
	{
		Provider::factory()->count(10)->create();
	}
}

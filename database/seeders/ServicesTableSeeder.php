<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Provider;

class ServicesTableSeeder extends Seeder
{
	/**
	 * Ejecutar: php artisan db:seed --class=DatabaseSeeder
	 */
	public function run()
	{
		Provider::all()->each(function($provider){
			Service::factory()->count(3)->create(['provider_id' => $provider->id]);
		});
	}
}

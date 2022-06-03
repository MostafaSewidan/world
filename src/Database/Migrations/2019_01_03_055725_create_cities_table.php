<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(config('world.migrations.cities.table_name'), function (Blueprint $table) {
			$table->id();
			$table->foreignId('country_id');
			$table->foreignId('state_id');
			$table->json('name');
            $table->integer('status')->default(1);

			foreach (config('world.migrations.cities.optional_fields') as $field => $value) {
				if ($value['required']) {
					$table->text($field, $value['length'] ?? null)->nullable();
				}
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists(config('world.migrations.cities.table_name'));
	}
}

<?php

namespace Nnjeim\World\Models;

use Nnjeim\World\Models\Traits\CityRelations;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
	use CityRelations , HasTranslations;

	protected $guarded = [];
    public $translatable 	= [ 'name','title' ];

	public $timestamps = false;

	/**
	 * Get the table associated with the model.
	 *
	 * @return string
	 */
	public function getTable(): string
	{
		return config('world.migrations.cities.table_name', parent::getTable());
	}
}

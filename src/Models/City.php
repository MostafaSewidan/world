<?php

namespace Nnjeim\World\Models;

use Nnjeim\World\Models\Traits\CityRelations;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
	use CityRelations , HasTranslations;

	protected $guarded = [];
    public $translatable 	= [ 'name' ];

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

    /**
     * Write code on Method
     *
     * @return response()
     */
    public static function boot()
    {
        parent::boot();

        /**
         * Write code on Method
         *
         * @return response()
         */
        static::creating(function ($item) {

            $item->name = [

                'ar' => $item->name,
                'en' => $item->name
            ];

            $item->title = [

                'ar' => $item->name,
                'en' => $item->name
            ];

            $item->save();
        });
    }
}

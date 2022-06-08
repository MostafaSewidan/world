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

	public $timestamps = true;

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
        static::created(function ($item) {

            $state_ar = include __DIR__.'/../../resources/lang/ar/cities.php';

            if(array_key_exists($item->id , $state_ar)){

                $title = [
                    'ar' => $state_ar[$item->id],
                    'en' => $item->name
                ];
            }else{

                $title = [
                    'ar' => $item->name,
                    'en' => $item->name
                ];
            }

            $item->name = $title;
            $item->title = $title;
            $item->save();
        });
    }
}

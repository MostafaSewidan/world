<?php

namespace Nnjeim\World\Models;

use Nnjeim\World\Models\Traits\StateRelations;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
	use StateRelations, HasTranslations;

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
		return config('world.migrations.states.table_name', parent::getTable());
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

            $state_ar = include __DIR__.'/../../resources/lang/ar/states.php';

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

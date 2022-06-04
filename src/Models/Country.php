<?php

namespace Nnjeim\World\Models;

use Nnjeim\World\Models\Traits\CountryRelations;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use CountryRelations, HasTranslations;

    protected $guarded = [];
    public $translatable = ['name'];

    public $timestamps = false;

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return config('world.migrations.countries.table_name', parent::getTable());
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

            $country_ar = include __DIR__.'/../../resources/lang/ar/country.php';

            if(array_key_exists($item->iso2 , $country_ar)){

                $title = [
                    'ar' => $country_ar[$item->iso2],
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

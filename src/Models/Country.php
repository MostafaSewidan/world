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


    public function setNameAttribute($value)
    {
        $country_ar = include '../../resources/lang/ar/country.php';
        $this->attributes['name'] = [
            'ar' => (isset($country_ar[$this->attributes['iso2']]) ? $country_ar[$this->attributes['iso2']] : $value),
            'en' => $value
        ];
    }
}

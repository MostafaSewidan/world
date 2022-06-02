<?php

namespace Nnjeim\World\Models;

use Nnjeim\World\Models\Traits\CurrencyRelations;
use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
	use CurrencyRelations , HasTranslations;

	protected $fillable = [
		'country_id',
		'name',
		'code',
		'precision',
		'symbol',
		'symbol_native',
		'symbol_first',
		'decimal_mark',
		'thousands_separator',
	];

	public $timestamps = false;
    public $translatable 	= [ 'name' ];

	/**
	 * Get the table associated with the model.
	 *
	 * @return string
	 */
	public function getTable(): string
	{
		return config('world.migrations.currencies.table_name', parent::getTable());
	}

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = [
            'ar' => $value,
            'en' => $value
        ];
    }
}

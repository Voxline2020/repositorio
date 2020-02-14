<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'companies';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
		'name',
		'slug',
		'sku',
		'barcode',
		'description',
		'use_mode',
		'obs',
		'ingredients',
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'name' => 'string'
  ];

	protected $attributes = [
			'name' => ""
	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'name' => 'required'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  // public function stores()
  // {
  //   return $this->hasMany(\App\Models\Store::class);
	// }
}

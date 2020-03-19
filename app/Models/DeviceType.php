<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class DeviceType extends Model
{
	use Notifiable;

	public $table = 'device_types';


  public $fillable = [
		'id',
    'name'

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
	 public function Devices()
  {
		return $this->hasMany(\App\Models\Device::class, 'id','type_id');
	}
}

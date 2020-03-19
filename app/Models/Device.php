<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Device extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'devices';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
		'height',
		'width',
		'computer_id',
		'imei',
		'type_id',
		'state',
		'version'
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'name' => 'string',
	'height'=> 'integer',
	'width'=> 'integer',
  ];

	protected $attributes = [
			'name' => "",
			'height'=> "",
			'width'=> "",
	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'name' => 'required',
		'height' => 'required',
		'width' => 'required',
		'computer_id'=>'required',
		'type_id'=>'required'
	];

	public function computer()
  	{
		return $this->belongsTo(\App\Models\Computer::class);
	}
	public function type()
  	{
		return $this->belongsTo(\App\Models\DeviceType::class, 'type_id','id');
	}
	public function eventAssignations()
  	{
    return $this->hasMany(\App\Models\EventAssignation::class);
	}


}

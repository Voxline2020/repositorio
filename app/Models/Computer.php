<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Computer extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'computers';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'nameComputer',
		'code',
		'location',
		'teamviewer_code',
		'teamviewer_pass',
		'aamyy_pass',
		'aamyy_code',
		'ip',
		'store_id',
		'type_id'
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'code' => 'string',
		'location'=> 'string',
		'teamviewer_code'=> 'string',
		'teamviewer_pass'=> 'string',
		'aamyy_pass'=> 'string',
		'aamyy_code'=> 'string',
		'ip'=> 'string',
		'store_id'=>'integer',
		'type_id'=>'integer'
  ];

	protected $attributes = [
			'code'=> "",
			'location'=> "",
			'teamviewer_code'=> "",
			'teamviewer_pass'=> "",
			'aamyy_pass'=> "",
			'aamyy_code'=> "",
			'ip'=> "",

	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
			'code'=> 'required',
			'location'=> 'required',
			'teamviewer_code'=> 'required',
			'teamviewer_pass'=> 'required',
			'aamyy_pass'=> 'required',
			'aamyy_code'=> 'required',
			'ip'=> 'required|ipv4',
			'store_id'=> 'required',
			'type_id'=>'required'

  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function store()
  {
    return $this->belongsTo(\App\Models\Store::class);
	}

	public function type()
  {
		return $this->belongsTo(\App\Models\AccessType::class, 'type_id','id');
	}

	public function pivots()
	{
		return $this->hasMany(\App\Models\ComputerPivot::class);
	}

	public function devices()
	{
		return $this->hasMany(\App\Models\Device::class);
	}
}

<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ComputerPivot extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'computer_pivots';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
		'code',
		'pass',
		'ip',
		'location',
		'teamviewer_code',
		'teamviewer_pass',
		'company_id'
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
		'name'=>'string',
		'code'=>'string',
		'pass'=>'string',
		'ip'=>'string',
		'location'=>'string',
		'teamviewer_code'=>'string',
		'teamviewer_pass'=>'string',
		'company_id'=>'integer'
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
  public function computers()
  {
    return $this->hasMany(\App\Models\Computer::class);
	}
  public function onpivots()
  {
    return $this->hasMany(\App\Models\ComputerOnPivot::class);
	}

}

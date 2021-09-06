<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class AccessType extends Model
{
	use Notifiable;

	public $table = 'access_types';


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
	 public function Computers()
  {
		return $this->hasMany(\App\Models\Computer::class, 'id','type_id');
	}
}

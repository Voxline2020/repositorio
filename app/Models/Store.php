<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Store extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'stores';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
		'address',
		'lat',
		'lng',
		'company_id'
	];

	// public static function stores($id){
	// 		return Store::where('company_id','=',$id)
	// 		->get();
	// }


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'name' => 'string',
		'address'=> 'string',
		'lat'=> 'string',
		'lng'=> 'string',
		'company_id'=>'integer'
  ];

	protected $attributes = [
			'name' => "",
			'address'=> "",
			'lat'=> "",
			'lng'=> ""
	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'name' => 'required',
		'address' => 'required',
		'lat' => 'required',
		'lng' => 'required',
		'company_id'=>'required'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function computers()
  {
    return $this->hasMany(\App\Models\Computer::class);
	}

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class);
	}
	public function screens()
	{
	  return $this->hasMany(\App\Models\Screen::class);
	  }
}

<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;



/**
 * Class User
 * @package App\Models
 * @version July 26, 2019, 3:18 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection contents
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection logs
 * @property \Illuminate\Database\Eloquent\Collection playlists
 * @property \Illuminate\Database\Eloquent\Collection reportDetails
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property \Illuminate\Database\Eloquent\Collection
 * @property string email
 * @property string password
 * @property boolean state
 * @property string rut
 * @property string name
 * @property string lastname
 * @property string surname
 * @property string middlename
 */
class User extends Authenticatable
{
  use SoftDeletes;
	use Notifiable;
	use HasRoles;


  public $table = 'users';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'email',
    'password',
    'state',
    'rut',
    'name',
    'lastname',
    'surname',
		'middlename',
		'role',
  ];

	protected $hidden = [
			'password', 'remember_token',
	];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'email' => 'string',
    'password' => 'string',
    'state' => 'boolean',
    'rut' => 'string',
    'name' => 'string',
    'lastname' => 'string',
    'surname' => 'string',
		'middlename' => 'string',
  ];

	protected $attributes = [
			'state' => 1,
			'surname' => "",
			'middlename' => "",
			'lastname' => "",
			'rut' => ""
	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'email' => 'required',
		'password' => 'required',
		'rut' => 'required|max:12',
		'name' => 'required|max:120',
		'lastname' => 'required|max:120',
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function contents()
  {
    return $this->hasMany(\App\Models\Content::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function logs()
  {
    return $this->hasMany(\App\Models\Log::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function playlists()
  {
    return $this->hasMany(\App\Models\Playlist::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function reportDetails()
  {
    return $this->hasMany(\App\Models\ReportDetail::class);
  }

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class, 'company_id');
	}

	public function getShortName()
	{
			return "{$this->name} {$this->lastname}";
	}
	public function getStateNameAttribute()
	{
		if($this->state == 0){
			return "Inactivo";
		}
		else if($this->state = 1) {
			return "Activo";

		}
	}

	public function getFullNameAttribute()
	{
			return "{$this->name} {$this->middlename} {$this->lastname} {$this->surname}";
	}

	public function getNamesOfRolesAttribute(){
		$names = "";
		$qty = $this->getRoleNames()->count();
		foreach($this->getRoleNames() as $key=> $name ){
			$names .= $name ;
			if($key+1 < $qty){
				$names .= ", ";
			}
		};
		return $names;
	}

	public function getRoleAttribute(){
		$name = $this->getRoleNames()->first();
		// foreach($this->getRoleNames() as $key=> $name ){
		// 	$names .= $name ;
		// 	if($key+1 < $qty){
		// 		$names .= ", ";
		// 	}
		// };
		return $name;

	}

}

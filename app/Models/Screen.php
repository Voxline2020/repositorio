<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Screen extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'screens';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
		'height',
		'width',
		'computer_id',
		'playlist_id',
		'state'
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
		'computer_id'=>'required'
	];

	public function computer()
  {
		return $this->belongsTo(\App\Models\Computer::class);
	}

	public function screenPlaylistAsignations()
	{
			return $this->hasMany(\App\Models\ScreenPlaylistAsignation::class);
	}

	public function playlist()
	{
		return $this->belongsTo(\App\Models\Playlist::class);
	}


}

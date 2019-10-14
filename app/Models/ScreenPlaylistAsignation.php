<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ScreenPlaylistAsignation extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'screen_playlist_asignation';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'screen_id',
		'version_id',
		'active',
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'screen_id' => 'required',
		'version_id' => 'required',
		'active' => 'required'
  ];

}

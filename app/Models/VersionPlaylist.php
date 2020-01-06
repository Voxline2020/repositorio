<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class VersionPlaylist extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'version_playlists';

  const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
		'slug',
		'version',
		'state',
		'playlist_id'
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

  ];

	public function playlist()
	{
			return $this->belongsTo(\App\Models\Playlist::class);
	}

	public function versionPlaylistDetails()
	{
			return $this->hasMany(\App\Models\VersionPlaylistDetail::class);
	}


	public function getStateStringAttribute()
	{
		if($this->state == 0){
			return "Inactivo";
		}
		return "Activo";
	}


}

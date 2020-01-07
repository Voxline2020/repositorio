<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class VersionPlaylistDetail extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'version_playlist_details';

  const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

  public $fillable = [
    'content_id',
		'version_playlist_id',
			'orderContent'
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */

	protected $casts = [
		'content_id' => 'integer',
		'version_playlist_id' => 'integer',
		'orderContent' => 'integer'
	];


  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'content_id' => 'required',
		'version_playlist_id' => 'required',
		'orderContent' => 'required'
  ];

	public function content()
	{
			return $this->belongsTo(\App\Models\Content::class);
	}

	public function contentWithTrashed()
	{
			return $this->belongsTo(\App\Models\Content::class, 'content_id')->withTrashed();
	}


	public function versionPlaylist()
	{
			return $this->belongsTo(\App\Models\VersionPlaylist::class, 'version_playlist_id');
	}


}

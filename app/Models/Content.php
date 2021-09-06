<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Content
 * @package App\Models
 * @version August 1, 2019, 4:30 pm -04
 *
 * @property \App\Models\Event event
 * @property \App\Models\User user
 * @property \Illuminate\Database\Eloquent\Collection detailContentTags
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection versionPlaylistDetails
 * @property string name
 * @property string location
 * @property integer user_id
 * @property integer size
 * @property integer width
 * @property integer height
 * @property string event_id
 * @property string duration
 */
class Content extends Model
{
  use SoftDeletes;

  public $table = 'contents';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'name',
    'location',
    'user_id',
    'size',
    'width',
		'height',
		'duration',
    'event_id',
    'slug',
		'filetype',
		'mime',
		'original_name'
  ];

  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
    'id' => 'integer',
    'name' => 'string',
    'location' => 'string',
    'user_id' => 'integer',
    'size' => 'integer',
    'width' => 'integer',
		'height' => 'integer',
		'duration' => 'time',
    'event_id' => 'integer',
    'slug' => 'string',
  ];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
    'user_id' => 'required',
		'name' => 'required'
  ];

	// public static function messages()
	// {
	// 		return [
	// 				'name.required' => 'A title is required',
	// 		];
	// }


	public static function attributes()
	{
			return [
					'name' => 'nombre',
			];
	}
  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function event()
  {
    return $this->belongsTo(\App\Models\Event::class);
	}
	public function eventAssignations()
  {
    return $this->hasMany(\App\Models\EventAssignation::class);
	}

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   **/
  public function user()
  {
    return $this->belongsTo(\App\Models\User::class, 'user_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/
  public function detailContentTags()
  {
    return $this->hasMany(\App\Models\DetailContentTag::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   **/


	public function getResolutionAttribute()
	{
		return $this->width.'x'.$this->height;
	}

	public function getSizeMBAttribute()
	{
		return number_format($this->size/1000000, 3)." mb";
	}
	public function getDurationModAttribute()
	{
		$duration = $this->duration;
		$extract [] = explode(':',$duration);
		$parts = $extract[0];
		$hour = $parts[0];
		$min = $parts[1];
		$seg = $parts[2];
		if ($hour=='00' && $min=='00'){
			$mod = $seg.' seg.';
		}else if($hour=='00'){
			$mod = $min.' min '.$seg.' seg.';
		}else {
			if($hour=='01'){
				$mod=$hour.' hora '.$min.' min '.$seg.' seg.';
			}else{
				$mod=$hour.' horas '.$min.' min '.$seg.' seg.';
			}
		}
		return $mod;
	}


}

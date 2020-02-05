<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon as Carbon;

/**
 * Class Event
 * @package App\Models
 * @version August 14, 2019, 5:31 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection contents
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property string name
 * @property string|\Carbon\Carbon initdate datetime(0)
 * @property string|\Carbon\Carbon enddate datetime(0)
 * @property boolean state
 * @property string slug
 */
class EventAssignation extends Model
{
    use SoftDeletes;

    public $table = 'event_assignations';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'content_id',
        'screen_id',
        'order',
        'user_id',
        'state'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'content_id' => 'int',
        'screen_id' => 'int',
        'order' => 'int',
        'user_id' => 'int',
        'state' => 'int'
		];

		protected $attributes = [
				'content_id' => "",
        'screen_id' => "",
        'order' => "",
        'user_id' => "",
        'state' => ""

	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'content_id' => 'required',
		'screen_id'=> 'required',
		'user_id'=> 'required'

  ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function content()
    {
        return $this->belongsTo(\App\Models\Content::class);
		}
		public function screen()
    {
        return $this->belongsTo(\App\Models\Screen::class);
    }

}

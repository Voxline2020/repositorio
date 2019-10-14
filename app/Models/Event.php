<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
class Event extends Model
{
    use SoftDeletes;

    public $table = 'events';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'initdate',
        'enddate',
        'state',
        'slug',
				'company_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'initdate' => 'dateTime-local',
        'enddate' => 'dateTime-local',
        'state' => 'boolean',
        'slug' => 'string',
			'company_id' =>'integer'
		];

		protected $attributes = [
        'name' => "",
        'initdate' => "",
        'enddate' => "",
        'state' => "",
        'slug' => ""

	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [
		'name' => 'required',
		'initdate'=> 'required',
		'enddate'=> 'required'

  ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function contents()
    {
        return $this->hasMany(\App\Models\Content::class);
    }

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class, 'company_id');
	}

}

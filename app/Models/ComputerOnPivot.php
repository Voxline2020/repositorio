<?php

namespace App\Models;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ComputerOnPivot extends Model
{
	use SoftDeletes;
	use Notifiable;

	public $table = 'computer_on_pivots';

  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';

  public $fillable = [
    'computer_id',
		'computer_pivot_id',
		'state',
  ];


  /**
   * The attributes that should be casted to native types.
   *
   * @var array
   */
  protected $casts = [
		'computer_id'=>'integer',
		'computer_pivot_id'=>'integer',
		'state'=>'string',
  ];

	protected $attributes = [

	];

  /**
   * Validation rules
   *
   * @var array
   */
  public static $rules = [

  ];


	public function computerPivot()
	{
		return $this->belongsTo(\App\Models\ComputerPivot::class,'computer_pivot_id', 'id');
	}

	public function computer()
	{
		return $this->belongsTo(\App\Models\Computer::class);
	}
}

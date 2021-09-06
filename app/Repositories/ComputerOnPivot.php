<?php

namespace App\Repositories;

use App\Models\ComputerOnPivot;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 26, 2019, 3:18 pm UTC
*/

class ComputerOnPivotRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
			'nameComputer',
			'code',
			'location',
			'teamviewer_code',
			'teamviewer_pass',
			'aamyy_pass',
			'aamyy_code',
			'ip',
			'store_id',
			'type_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ComputerOnPivot::class;
    }
}

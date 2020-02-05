<?php

namespace App\Repositories;

use App\Models\ComputerPivot;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 26, 2019, 3:18 pm UTC
*/

class ComputerPivotRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
			'name',
			'code',
			'pass',
			'ip',
			'location',
			'teamviewer_code',
			'teamviewer_pass',
			'company_id'
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
        return ComputerPivot::class;
    }
}

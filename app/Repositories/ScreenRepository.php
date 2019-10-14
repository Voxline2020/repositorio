<?php

namespace App\Repositories;

use App\Models\Screen;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 26, 2019, 3:18 pm UTC
*/

class ScreenRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
			'name',
			'height',
			'width',
			'computer_id'
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
        return Screen::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\Content;
use App\Repositories\BaseRepository;

/**
 * Class ContentRepository
 * @package App\Repositories
 * @version August 1, 2019, 4:30 pm -04
*/

class ContentRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'location',
        'user_id',
        'size',
        'width',
        'height',
        'event_id'
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
        return Content::class;
    }
}

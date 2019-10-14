<?php

namespace App\Repositories;

use App\Models\ScreenPlaylistAsignation;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 26, 2019, 3:18 pm UTC
*/

class ScreenPlaylistAsignationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
			'screen_id',
		'version_id',
		'active'
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
        return ScreenPlaylistAsignation::class;
    }
}

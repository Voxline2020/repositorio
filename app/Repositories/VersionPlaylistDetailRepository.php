<?php

namespace App\Repositories;

use App\Models\VersionPlaylistDetail;
use App\Repositories\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version July 26, 2019, 3:18 pm UTC
*/

class VersionPlaylistDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
		'content_id',
		'version_id'
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
        return VersionPlaylistDetailRepository::class;
    }
}

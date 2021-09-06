<?php

namespace App\Repositories;

use App\Models\Playlist;
use App\Repositories\BaseRepository;

/**
 * Class PlaylistRepository
 * @package App\Repositories
 * @version August 9, 2019, 1:31 pm -04
*/

class PlaylistRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'slug',
        'user_id'
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
        return Playlist::class;
    }
}

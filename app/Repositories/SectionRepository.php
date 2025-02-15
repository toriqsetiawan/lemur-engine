<?php

namespace App\Repositories;

use App\Models\Section;

/**
 * Class SectionRepository
 * @package App\Repositories
 * @version January 6, 2021, 1:01 pm UTC
*/

class SectionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'slug',
        'name',
        'type',
        'order',
        'default_state',
        'is_protected'
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
        return Section::class;
    }
}

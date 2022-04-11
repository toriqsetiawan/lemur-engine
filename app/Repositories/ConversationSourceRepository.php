<?php

namespace App\Repositories;

use App\Models\ConversationSource;

/**
 * Class ConversationSourceRepository
 * @package App\Repositories
 * @version April 13, 2022, 2:03 pm UTC
*/

class ConversationSourceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conversation_id',
        'slug',
        'params',
        'user',
        'ip',
        'user_agent',
        'referer'
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
        return ConversationSource::class;
    }
}

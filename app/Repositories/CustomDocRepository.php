<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\BotKey;
use App\Models\CustomDoc;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CustomDocRepository
 * @package App\Repositories
 * @version April 4, 2021, 9:42 am UTC
*/

class CustomDocRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'slug',
        'title',
        'body',
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
        return CustomDoc::class;
    }
}

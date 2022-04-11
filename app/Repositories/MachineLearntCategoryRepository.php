<?php

namespace App\Repositories;

use App\Models\Bot;
use App\Models\Client;
use App\Models\ClientCategory;
use App\Models\MachineLearntCategory;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class MachineLearntCategoryRepository
 * @package App\Repositories
 * @version January 21, 2021, 7:55 am UTC
*/

class MachineLearntCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'bot_id',
        'turn_id',
        'slug',
        'pattern',
        'template',
        'topic',
        'that',
        'example_input',
        'example_output',
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
        return MachineLearntCategory::class;
    }
}

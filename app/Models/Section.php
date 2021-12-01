<?php

namespace App\Models;

use App\Traits\UiValidationTrait;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @SWG\Definition(
 *      definition="Section",
 *      required={"user_id", "slug", "name", "type", "order","default_state", "is_protected" },
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="slug",
 *          description="slug",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="order",
 *          description="order",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="default_state",
 *          description="default_state",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="is_protected",
 *          description="is_protected",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="deleted_at",
 *          description="deleted_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Section extends Model
{
    use SoftDeletes;
    use UiValidationTrait;
    use HasSlug;


    public $table = 'sections';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'slug',
        'name',
        'type',
        'order',
        'default_state',
        'is_protected'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'type' => 'string',
        'order'=> 'integer',
        'default_state'=> 'string',
        'is_protected' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|unique:sections,name|string|max:255',

    ];

    /**
     * Add the user_id on create
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $loggedInUser = Auth::user();
            //set the user to the current logged in user
            $model->user_id = $loggedInUser->id;
            //if the user is not an admin overwrite is master with 0
            if (!$loggedInUser->hasRole('admin')) {
                $model->is_master = 0;
            }
        });
    }

    /**
     * Get the validation rules
     *
     * return array
     */
    public function getRules()
    {
        return self::$rules;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }



    /**
    * the query that is run in the datatable
    *
    * @return mixed
    */
    public function dataTableQuery()
    {

            return Section::select([$this->table.'.*','users.email'])
                ->leftJoin('users', 'users.id', '=', $this->table.'.user_id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }


    public static function getAllSectionsForCategoryGroups($allCategoryGroups){
        $idList = array_keys($allCategoryGroups);
        $allSections = Section::WhereIn('id',$idList)->orderBy('order', 'ASC')->get();
        $cleanAllSections = [];


        $miscSection = new Section();
        $miscSection->id = null;
        $miscSection->slug = 'misc';
        $miscSection->name = 'Misc';
        $miscSection->order = 999;
        $miscSection->default_state = 'closed';
        $allSections->push($miscSection);


        foreach($allSections as $section){
            $cleanAllSections[$section->id] = $section;

        }
        return $cleanAllSections;
    }



}

<?php

use App\Models\CategoryGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{

    protected $mappingArr =
        [
            "std-religion"=>"std-happybirthday",
            "std-that"=>"std-religion",
            "std-jokes"=>"std-that",
            "std-sales"=>"std-jokes",
            "std-turing"=>"std-sales",
            "std-knockknock"=>"std-turing",
            "std-sextalk"=>"std-knockknock",
            "std-yesno"=>"std-sextalk",
            "std-yomama"=>"std-yesno",
            "std-shutup"=>"std-yomama",
            "std-learn"=>"std-shutup",
            "std-srai"=>"std-learn",
            "std-horoscope"=>"std-srai",
            "std-robot"=>"std-horoscope",
            "std-happybirthday"=>"std-sports",
            "std-sports"=>"std-howmany",
            "std-howmany"=>"std-suffixes",
            "std-suffixes"=>"std-robot",
        ];

    protected $idChecker = [
        "std-religion" => 23,
        "std-robot" => 24,
        "std-sports"=> 25,
        "std-sales"=> 26,
        "std-sextalk"=> 27,
        "std-srai"=> 28,
        "std-that"=> 29,
        "std-suffixes"=> 30,
        "std-turing"=> 31,
        "std-yesno"=> 32,
        "std-learn"=> 33,
        "std-happybirthday"=> 34,
        "std-horoscope"=> 35,
        "std-howmany"=> 36,
        "std-jokes"=> 37,
        "std-knockknock"=> 38,
        "std-yomama"=> 39,
        "std-shutup"=> 40
    ];


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(BotsTableSeeder::class);
        $this->call(BotPropertiesTableSeeder::class);
        $this->call(WordSpellingGroupsTableSeeder::class);
        $this->call(WordSpellingsTableSeeder::class);
        $this->call(WordTransformationsTableSeeder::class);
        $this->call(CategoryGroupsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(BotCategoryGroupsTableSeeder::class);

        $this->fixCategoryMisMatch();


    }

    /**
     * this method corrects errors in the category to category_group mismatching
     *
     * @return bool
     * @throws Exception
     */
    public function fixCategoryMisMatch(){



            $mismatchedFiles = ["std-religion","std-robot","std-sports","std-sales","std-sextalk","std-srai",
                "std-that","std-suffixes","std-turing","std-yesno","std-learn","std-happybirthday","std-horoscope",
                "std-howmany","std-jokes","std-knockknock","std-yomama","std-shutup"];

            $all = CategoryGroup::whereIn('slug',$mismatchedFiles)->withTrashed();

            if($all->count()<=0){
                echo "No Data - nothing to correct";
                return true;
            }

            foreach($all->get() as $item) {

                if($this->idChecker[$item->slug]!==$item->id){
                    throw new Exception("Exiting cannot guarantee that the IDs are the 100% correct. This migration should be checked and performed manually");
                }

                $id = $item->slug;
                $original[$id]['id'] = $item->id;
                $original[$id]['slug'] = $item->slug;
                $original[$id]['user_id'] = $item->user_id;
                $original[$id]['language_id'] = $item->language_id;
                $original[$id]['name'] = $item->name;
                $original[$id]['description'] = $item->description;
                $original[$id]['status'] = $item->status;
                $original[$id]['is_master'] = $item->is_master;
                $original[$id]['deleted_at'] = $item->deleted_at;

            }


            foreach($this->mappingArr as $mapFrom => $mapTo) {


                $groupToUpdate = CategoryGroup::where('id', $original[$mapFrom]['id'])->withTrashed()->first();
                echo "\nUpdating: ".$groupToUpdate->name ." to ".$original[$mapTo]['name'];

                $slugChecker = CategoryGroup::where('slug',$original[$mapTo]['slug'])->withTrashed()->first();

                if($slugChecker!==null){
                    $groupToUpdate->slug = $original[$mapTo]['slug'].'---remapped';
                    $groupToUpdate->name = $original[$mapTo]['name'].'---remapped';
                }else{
                    $groupToUpdate->slug = $original[$mapTo]['slug'];
                    $groupToUpdate->name = $original[$mapTo]['name'];
                }

                $groupToUpdate->description = $original[$mapTo]['description'];
                $groupToUpdate->language_id = $original[$mapTo]['language_id'];
                $groupToUpdate->status = $original[$mapTo]['status'];
                $groupToUpdate->is_master = $original[$mapTo]['is_master'];
                $groupToUpdate->deleted_at = $original[$mapTo]['deleted_at'];
                $groupToUpdate->save();

            }

        $typo = CategoryGroup::where('slug','like','%---remapped')->get();
        foreach($typo as $item) {

            $item->name = str_replace('---remapped','',$item->name);
            $item->slug = str_replace('---remapped','',$item->slug);
            $item->save();

        }



    }
}

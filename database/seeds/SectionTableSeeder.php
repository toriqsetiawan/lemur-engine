<?php

use App\Models\BotProperty;
use App\Models\CategoryGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('sections')->insertOrIgnore([
            [
                'id' => 1,
                'user_id' => '1',
                'slug' => 'test-categories',
                'name' => 'Test Categories',
                'order' => '1000',
                'type' => 'CATEGORY_GROUP',
                'default_state'=>'closed',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 2,
                'user_id' => '1',
                'slug' => 'conversation-flow',
                'name' => 'Conversation Flow',
                'order' => '2',
                'type' => 'CATEGORY_GROUP',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 3,
                'user_id' => '1',
                'slug' => 'personality',
                'name' => 'Personality',
                'order' => '1',
                'type' => 'CATEGORY_GROUP',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 4,
                'user_id' => '1',
                'slug' => 'general-knowledge',
                'name' => 'General Knowledge',
                'order' => '1',
                'type' => 'CATEGORY_GROUP',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 5,
                'user_id' => '1',
                'slug' => 'bot-personality',
                'name' => 'Bot Personality',
                'order' => '1',
                'type' => 'BOT_PROPERTY',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 6,
                'user_id' => '1',
                'slug' => 'social-media',
                'name' => 'Social Media',
                'order' => '2',
                'type' => 'BOT_PROPERTY',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],
            [
                'id' => 7,
                'user_id' => '1',
                'slug' => 'special-features',
                'name' => 'Special Features',
                'order' => '1',
                'type' => 'CATEGORY_GROUP',
                'default_state'=>'open',
                'is_protected' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

            ],

        ]);

        //some people are updating their eixsting so we will retro add the sections...
        BotProperty::where('slug','age')->update(['section_id'=>5]);
        BotProperty::where('slug','baseballteam')->update(['section_id'=>5]);
        BotProperty::where('slug','birthday')->update(['section_id'=>5]);
        BotProperty::where('slug','birthplace')->update(['section_id'=>5]);
        BotProperty::where('slug','botmaster')->update(['section_id'=>5]);
        BotProperty::where('slug','boyfriend')->update(['section_id'=>5]);
        BotProperty::where('slug','build')->update(['section_id'=>5]);
        BotProperty::where('slug','celebrities')->update(['section_id'=>5]);
        BotProperty::where('slug','celebrity')->update(['section_id'=>5]);
        BotProperty::where('slug','class')->update(['section_id'=>5]);
        BotProperty::where('slug','email')->update(['section_id'=>6]);
        BotProperty::where('slug','emotions')->update(['section_id'=>5]);
        BotProperty::where('slug','ethics')->update(['section_id'=>5]);
        BotProperty::where('slug','etype')->update(['section_id'=>5]);
        BotProperty::where('slug','family')->update(['section_id'=>5]);
        BotProperty::where('slug','favoriteactor')->update(['section_id'=>5]);
        BotProperty::where('slug','favoriteactress')->update(['section_id'=>5]);
        BotProperty::where('slug','favoriteartist')->update(['section_id'=>5]);
        BotProperty::where('slug','favoriteauthor')->update(['section_id'=>5]);
        BotProperty::where('slug','favoriteband')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritebook')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritecolor')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritefood')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritemovie')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritesong')->update(['section_id'=>5]);
        BotProperty::where('slug','favoritesport')->update(['section_id'=>5]);
        BotProperty::where('slug','feelings')->update(['section_id'=>5]);
        BotProperty::where('slug','footballteam')->update(['section_id'=>5]);
        BotProperty::where('slug','forfun')->update(['section_id'=>5]);
        BotProperty::where('slug','friend')->update(['section_id'=>5]);
        BotProperty::where('slug','friends')->update(['section_id'=>5]);
        BotProperty::where('slug','gender')->update(['section_id'=>5]);
        BotProperty::where('slug','genus')->update(['section_id'=>5]);
        BotProperty::where('slug','girlfriend')->update(['section_id'=>5]);
        BotProperty::where('slug','hockeyteam')->update(['section_id'=>5]);
        BotProperty::where('slug','kindmusic')->update(['section_id'=>5]);
        BotProperty::where('slug','kingdom')->update(['section_id'=>5]);
        BotProperty::where('slug','language')->update(['section_id'=>5]);
        BotProperty::where('slug','location')->update(['section_id'=>5]);
        BotProperty::where('slug','looklike')->update(['section_id'=>5]);
        BotProperty::where('slug','master')->update(['section_id'=>5]);
        BotProperty::where('slug','msagent')->update(['section_id'=>5]);
        BotProperty::where('slug','name')->update(['section_id'=>5]);
        BotProperty::where('slug','nationality')->update(['section_id'=>5]);
        BotProperty::where('slug','order')->update(['section_id'=>5]);
        BotProperty::where('slug','orientation')->update(['section_id'=>5]);
        BotProperty::where('slug','party')->update(['section_id'=>5]);
        BotProperty::where('slug','phylum')->update(['section_id'=>5]);
        BotProperty::where('slug','president')->update(['section_id'=>5]);
        BotProperty::where('slug','question')->update(['section_id'=>5]);
        BotProperty::where('slug','religion')->update(['section_id'=>5]);
        BotProperty::where('slug','sign')->update(['section_id'=>5]);
        BotProperty::where('slug','size')->update(['section_id'=>5]);
        BotProperty::where('slug','species')->update(['section_id'=>5]);
        BotProperty::where('slug','talkabout')->update(['section_id'=>5]);
        BotProperty::where('slug','version')->update(['section_id'=>5]);
        BotProperty::where('slug','vocabulary')->update(['section_id'=>5]);
        BotProperty::where('slug','wear')->update(['section_id'=>5]);
        BotProperty::where('slug','website')->update(['section_id'=>6]);


        CategoryGroup::where('slug','dev-testcases')->update(['section_id'=>1]);
        CategoryGroup::where('slug','std-critical')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-rating')->update(['section_id'=>7]);
        CategoryGroup::where('slug','std-hello')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-65percent')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-atomic')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-botmaster')->update(['section_id'=>3]);
        CategoryGroup::where('slug','std-brain')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-dictionary')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-howto')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-inventions')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-gender')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-geography')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-german')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-gossip')->update(['section_id'=>7]);
        CategoryGroup::where('slug','std-knowledge')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-lizards')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-numbers')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-personality')->update(['section_id'=>3]);
        CategoryGroup::where('slug','std-pickup')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-politics')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-profile')->update(['section_id'=>3]);
        CategoryGroup::where('slug','std-religion')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-robot')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-sports')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-sales')->update(['section_id'=>7]);
        CategoryGroup::where('slug','std-sextalk')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-srai')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-that')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-suffixes')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-turing')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-yesno')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-learn')->update(['section_id'=>7]);
        CategoryGroup::where('slug','std-happybirthday')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-horoscope')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-howmany')->update(['section_id'=>2]);
        CategoryGroup::where('slug','std-jokes')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-knockknock')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-yomama')->update(['section_id'=>4]);
        CategoryGroup::where('slug','std-shutup')->update(['section_id'=>4]);

    }
}

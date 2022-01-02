<?php

use App\Models\BotProperty;
use App\Models\CategoryGroup;
use App\Models\Section;
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

        $botPropertySections = config('lemur_section.bot_properties.sections');
        $botPropertyFields = config('lemur_section.bot_properties.fields');
        foreach($botPropertySections as $botPropertySectionSlug => $botPropertySectionSettings ){

            DB::table('sections')->insertOrIgnore([
                [
                    'user_id' => '1',
                    'slug' => $botPropertySectionSlug,
                    'name' => $botPropertySectionSettings['name'],
                    'order' => $botPropertySectionSettings['order'],
                    'type' => 'BOT_PROPERTY',
                    'default_state'=>$botPropertySectionSettings['default_state'],
                    'is_protected' => true,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]]);
        }
        foreach($botPropertyFields as $botPropertyFieldSlug => $botPropertySectionSlug ){
            $section = Section::where('slug',$botPropertySectionSlug)->where('type', 'BOT_PROPERTY')->firstOrFail();
            BotProperty::where('slug',$botPropertyFieldSlug)->update(['section_id'=>$section->id]);
        }

        #-------------------------------------------

        $categoryGroupSections = config('lemur_section.category_groups.sections');
        $categoryGroupsFields = config('lemur_section.category_groups.fields');

        foreach($categoryGroupSections as $categoryGroupSectionSlug => $categoryGroupSectionSettings ){
            DB::table('sections')->insertOrIgnore([
                [
                    'user_id' => '1',
                    'slug' => $categoryGroupSectionSlug,
                    'name' => $categoryGroupSectionSettings['name'],
                    'order' => $categoryGroupSectionSettings['order'],
                    'type' => 'CATEGORY_GROUP',
                    'default_state'=>$categoryGroupSectionSettings['default_state'],
                    'is_protected' => true,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]]);
        }

        foreach($categoryGroupsFields as $categoryGroupFieldSlug => $categoryGroupSlug ){
            $section = Section::where('slug',$categoryGroupSlug)->where('type', 'CATEGORY_GROUP')->firstOrFail();
            CategoryGroup::where('slug',$categoryGroupFieldSlug)->update(['section_id'=>$section->id]);
        }

    }
}

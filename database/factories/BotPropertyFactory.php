<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BotProperty;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(BotProperty::class, function (Faker $faker) {

    $name = uniqid($faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    $secton_ids = DB::table('sections')->where('type','BOT_PROPERTY')->pluck('id', 'id');
    $secton_id = $faker->randomElement($secton_ids);

    return [
        'bot_id' => $bot_id,
        'user_id' => $user_id,
        'section_id' => $secton_id,
        'slug' => $name,
        'name' => $name,
        'value' => $faker->word,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()

    ];
});

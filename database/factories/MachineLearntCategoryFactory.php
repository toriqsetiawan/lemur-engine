<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ClientCategory;
use App\Models\MachineLearntCategory;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(MachineLearntCategory::class, function (Faker $faker) {


    $client_ids = DB::table('clients')->pluck('id', 'id');
    $client_id = $faker->randomElement($client_ids);

    $bot_ids = DB::table('bots')->pluck('id', 'id');
    $bot_id = $faker->randomElement($bot_ids);

    $turn_ids = DB::table('turns')->pluck('id', 'id');
    $turn_id = $faker->randomElement($turn_ids);

    return [
        'client_id' => $client_id,
        'bot_id' => $bot_id,
        'turn_id' => $turn_id,
        'slug' => uniqid($faker->word, false),
        'pattern' => $faker->word,
        'template' => $faker->text,
        'topic' => $faker->word,
        'that' => $faker->text,
        'example_input' => $faker->text,
        'example_out' => $faker->text,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

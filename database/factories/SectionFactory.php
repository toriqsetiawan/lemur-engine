<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Section;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(Section::class, function (Faker $faker) {

    $word = uniqid($faker->word.$faker->word, false);

    $user_ids = DB::table('users')->pluck('id', 'id');
    $user_id = $faker->randomElement($user_ids);

    return [
        'user_id' => 1,
        'slug' => $word,
        'name' => $word,
        'type' => 'BP',
        'order' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

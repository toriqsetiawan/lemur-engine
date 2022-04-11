<?php

use App\Models\Bot;
use App\Models\Client;
use App\Models\Coin;
use App\Models\EmptyResponse;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'WelcomeController@index');

Auth::routes(['verify' => true,'register' => false]);

Route::get('/home', 'HomeController@index')
    ->middleware('auth:web');

Route::get('/botList', 'BotController@list')
    ->middleware('auth:web');


//Route::get('/profile', 'UserController@profile')->middleware('auth:web');
//Route::post('/profile', 'UserController@profileUpdate')->middleware('auth:web');
Route::get('/tokens', 'UserController@tokens')
    ->middleware('auth:web');
Route::post('/tokens', 'UserController@tokensUpdate')
    ->middleware('auth:web');


Route::resource('users', 'UserController')
    ->middleware('auth:web');

Route::resource('languages', 'LanguageController')
    ->middleware('auth:web');

Route::resource('bots', 'BotController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botCategoryGroups', 'BotCategoryGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('clients', 'ClientController')
    ->middleware(['auth:web','data.transform']);

Route::resource('conversations', 'ConversationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('maps', 'MapController')
    ->middleware('auth:web');

Route::resource('mapValues', 'MapValueController')
    ->middleware(['auth:web','data.transform']);

Route::resource('sections', 'SectionController')
    ->middleware('auth:web');

Route::resource('sets', 'SetController')
    ->middleware('auth:web');

Route::resource('setValues', 'SetValueController')
    ->middleware(['auth:web','data.transform']);

Route::GET('/test', 'TestController@index')
    ->middleware(['auth:web']);

Route::GET('/test/run', 'TestController@run')
    ->middleware(['auth:web']);

Route::resource('customDocs', 'CustomDocController')
    ->middleware(['auth:web','data.transform']);

/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/quickchat'], function () {

    Route::GET('/', 'BotController@quickChat')
        ->middleware('auth:web');

});


/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/category/fromEmptyResponse'], function () {

    Route::bind('emptyResponseSlug', function ($emptyResponseSlug) {
        try {
            $emptyResponse = App\Models\EmptyResponse::where('slug', $emptyResponseSlug)->firstOrFail();
            return $emptyResponse->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{emptyResponseSlug}', 'CategoryController@createFromEmptyResponse')
        ->middleware(['auth:web','data.transform']);
});


/** ---------------------------------------------------------------
 *  Create category from a turn
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/category/fromTurn'], function () {

    Route::bind('turnSlug', function ($turnSlug) {
        try {
            $turn = App\Models\Turn::where('slug', $turnSlug)->firstOrFail();
            return $turn->id;
        } catch (Exception $e) {
            abort(404);
        }
    });


    Route::GET('/{turnSlug}', 'CategoryController@createFromTurn')
        ->middleware(['auth:web','data.transform']);
});

Route::group(['prefix' => '/category/fromClientCategory'], function () {

    Route::bind('clientCategorySlug', function ($clientCategorySlug) {

        try {
            $clientCategory = App\Models\ClientCategory::where('slug', $clientCategorySlug)->firstOrFail();
            return $clientCategory->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{clientCategorySlug}', 'CategoryController@createFromClientCategory')
        ->middleware(['auth:web','data.transform']);
});

Route::group(['prefix' => '/category/fromMachineLearntCategory'], function () {

    Route::bind('machineLearntCategorySlug', function ($machineLearntCategorySlug) {

        try {
            $machineLearntCategory = App\Models\MachineLearntCategory::where('slug', $machineLearntCategorySlug)->firstOrFail();
            return $machineLearntCategory->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{machineLearntCategorySlug}', 'CategoryController@createFromMachineLearntCategory')
        ->middleware(['auth:web','data.transform']);
});


Route::group(['prefix' => '/category/fromCopy'], function () {

    Route::bind('categorySlug', function ($categorySlug) {

        try {
            $category = App\Models\Category::where('slug', $categorySlug)->firstOrFail();
            return $category->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{categorySlug}', 'CategoryController@createFromCopy')
        ->middleware(['auth:web','data.transform']);
});

Route::resource('categories', 'CategoryController')
    ->middleware(['auth:web','data.transform']);

Route::resource('normalizations', 'NormalizationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordSpellings', 'WordSpellingController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordTransformations', 'WordTransformationController')
    ->middleware(['auth:web','data.transform']);

Route::resource('conversationProperties', 'ConversationPropertyController')
    ->middleware(['auth:web','data.transform']);

Route::resource('conversationSources', 'ConversationSourceController')
    ->middleware(['auth:web','data.transform']);

Route::resource('clientCategories', 'ClientCategoryController')
    ->middleware(['auth:web','data.transform']);

Route::resource('machineLearntCategories', 'MachineLearntCategoryController')
    ->middleware(['auth:web','data.transform']);

Route::resource('emptyResponses', 'EmptyResponseController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botProperties', 'BotPropertyController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botWordSpellingGroups', 'BotWordSpellingGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('categoryGroups', 'CategoryGroupController')
    ->middleware(['auth:web','data.transform']);

Route::resource('turns', 'TurnController')
    ->middleware(['auth:web','data.transform']);

Route::resource('wordSpellingGroups', 'WordSpellingGroupController')
    ->middleware(['auth:web','data.transform']);

Route::Get('botList/create', 'BotController@create')
    ->middleware(['auth:web','data.transform']);

Route::resource('wildcards', 'WildcardController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botKeys', 'BotKeyController')
    ->middleware(['auth:web','data.transform']);

Route::resource('botAllowedSites', 'BotAllowedSiteController')
    ->middleware(['auth:web','data.transform']);

Route::delete('botRatings/reset', 'BotRatingController@reset')
    ->middleware(['auth:web','data.transform']);

Route::resource('botRatings', 'BotRatingController')
    ->middleware(['auth:web','data.transform']);


/** ---------------------------------------------------------------
 *  SUPER ADMIN BOT TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/bots'], function () {
    Route::bind('saBotSlug', function ($slug) {
        try {
            $bot = App\Models\Bot::where('slug', $slug)->withTrashed()->firstOrFail();
            return $bot;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saBotSlug}', 'BotController@slugUpdate')
        ->middleware('auth:web');
    Route::PATCH('/restore/{saBotSlug}', 'BotController@restore')
        ->middleware('auth:web');
    Route::DELETE('/{saBotSlug}', 'BotController@forceDestroy')
        ->middleware('auth:web');
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CUSTOM DOC TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/customDocs'], function () {
    Route::bind('saCustomDocSlug', function ($saCustomDocSlug) {
        try {
            $customDoc = App\Models\CustomDoc::where('slug', $saCustomDocSlug)->withTrashed()->firstOrFail();
            return $customDoc;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saCustomDocSlug}', 'CustomDocController@slugUpdate')
        ->middleware('auth:web');
    Route::PATCH('/restore/{saCustomDocSlug}', 'CustomDocController@restore')
        ->middleware('auth:web');
    Route::DELETE('/{saCustomDocSlug}', 'CustomDocController@forceDestroy')
        ->middleware('auth:web');
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN USER TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/users'], function () {
    Route::bind('saUserSlug', function ($slug) {
        try {
            $user = App\Models\User::where('slug', $slug)->withTrashed()->firstOrFail();
            return $user;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saUserSlug}', 'UserController@slugUpdate')
        ->middleware('auth:web');
    Route::PATCH('/restore/{saUserSlug}', 'UserController@restore')
        ->middleware('auth:web');
    Route::DELETE('/{saUserSlug}', 'UserController@forceDestroy')
        ->middleware('auth:web');
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CLIENT TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/clients'], function () {
    Route::bind('saClientSlug', function ($slug) {
        try {
            $client = App\Models\Client::where('slug', $slug)->firstOrFail();
            return $client;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saClientSlug}', 'ClientController@slugUpdate')
        ->middleware('auth:web');
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN LANGUAGE TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/languages'], function () {
    Route::bind('saLanguageSlug', function ($slug) {
        try {
            $language = App\Models\Language::where('slug', $slug)->firstOrFail();
            return $language;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saLanguageSlug}', 'LanguageController@slugUpdate')
        ->middleware('auth:web');
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN CONVERSATION TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/conversations'], function () {
    Route::bind('saConversationSlug', function ($slug) {
        try {
            $conversation = App\Models\Conversation::where('slug', $slug)->firstOrFail();
            return $conversation;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saConversationSlug}', 'ConversationController@slugUpdate')
        ->middleware('auth:web');
});


/** ---------------------------------------------------------------
 *  BOT EDIT ROUTER
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/bot'], function () {
    Route::bind('botSlug', function ($slug) {
        try {
            $bot = App\Models\Bot::where('slug', $slug)->firstOrFail();
            return $bot->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/properties/{botSlug}/list', 'BotController@botProperties')
    ->middleware('auth:web');
    Route::GET('/properties/{botSlug}/download', 'BotPropertyController@botPropertiesDownload')
    ->middleware('auth:web');
    Route::GET('/keys/{botSlug}/list', 'BotController@botKeys')
    ->middleware('auth:web');
    Route::GET('/sites/{botSlug}/list', 'BotController@botSites')
        ->middleware('auth:web');

    Route::GET('/categories/{botSlug}/list', 'BotController@botCategoryGroups')
    ->middleware('auth:web');
    Route::GET('/logs/{botSlug}/list', 'BotController@conversations')
    ->middleware('auth:web');
    Route::GET('/logs/{botSlug}/{conversationSlug}/download', 'ConversationController@downloadCsv')
        ->middleware('auth:web');
    Route::GET('/logs/{botSlug}/{conversationSlug}', 'BotController@conversations')
    ->middleware('auth:web');
    Route::GET('/plugins/{botSlug}/list', 'BotController@botPlugins')
    ->middleware('auth:web');
    Route::GET('/widget/{botSlug}/list', 'BotController@wigdets')
    ->middleware('auth:web');
    Route::GET('/stats/{botSlug}/list', 'BotController@stats')
    ->middleware('auth:web');
    Route::GET('/{botSlug}/chat', 'BotController@chatForm')
    ->middleware('auth:web');
    Route::POST('/{botSlug}/chat', 'BotController@chat')
    ->middleware('auth:web');
});


/** ---------------------------------------------------------------
 *  UPLOAD ROUTER
 ** -------------------------------------------------------------- */
Route::GET('categoriesUpload', 'CategoryController@uploadForm')
    ->middleware('auth:web');
Route::POST('categoriesUpload', 'CategoryController@upload')
    ->middleware('auth:web');

Route::GET('mapValuesUpload', 'MapValueController@uploadForm')
    ->middleware('auth:web');
Route::POST('mapValuesUpload', 'MapValueController@upload')
    ->middleware('auth:web');

Route::GET('setValuesUpload', 'SetValueController@uploadForm')
    ->middleware('auth:web');
Route::POST('setValuesUpload', 'SetValueController@upload')
    ->middleware('auth:web');

Route::GET('wordSpellingsUpload', 'WordSpellingController@uploadForm')
    ->middleware('auth:web');
Route::POST('wordSpellingsUpload', 'WordSpellingController@upload')
    ->middleware('auth:web');

Route::GET('wordTransformationsUpload', 'WordTransformationController@uploadForm')
    ->middleware('auth:web');
Route::POST('wordTransformationsUpload', 'WordTransformationController@upload')
    ->middleware('auth:web');

Route::GET('botPropertiesUpload', 'BotPropertyController@uploadForm')
    ->middleware('auth:web');
Route::POST('botPropertiesUpload', 'BotPropertyController@upload')
    ->middleware('auth:web');


Route::GET('/categories/{categoryGroupSlug}/download/csv', 'CategoryController@downloadCsv')
    ->middleware('auth:web');
Route::GET('/categories/{categoryGroupSlug}/download/aiml', 'CategoryController@downloadAiml')
    ->middleware('auth:web');

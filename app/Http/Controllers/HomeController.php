<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $authorBots = Bot::where('user_id', Auth::user()->id)->orderBy('name')->get();

        $publicBots = Bot::where('user_id', '!=', Auth::user()->id)->where('is_public', 1)->orderBy('name')->get();

        return view('home')->with(
            [
            'authorBots'=>$authorBots, 'publicBots'=>$publicBots]
        );
    }
}

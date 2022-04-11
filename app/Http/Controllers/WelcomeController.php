<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('welcome');
    }
}

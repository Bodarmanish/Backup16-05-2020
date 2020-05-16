<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
class ShowApiController extends Controller
{
    public function showapi(){
        return view('api-document.home');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Article;
use App\Category;
use App\Event;
use App\join;
use App\Rating;
use App\User;
use App\Comment;

class LeaderboardController extends Controller
{
    public function show()
    {

    	$categories = Category::get();
    	$users = User::get();
    	$articles =Article::get();
    
    	return view('leaderboard',compact('categories','users','articles'));
    }
}

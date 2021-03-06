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

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $categories = Category::where('category_id',$id)->get();
        $i=0;
        $categories = $categories->toArray();
        if(empty($categories))
        {
            $errorcode=6;
            return view('errors.503','errorcode');
        }
        $category = $categories[0];
        $articles = Article::join('users','users.user_id','=','articles.user_id')
                    ->join('categories','categories.category_id','=','articles.category_id')
                    ->select('articles.*','users.username','users.image','categories.category_id','categories.category_name')
                    ->where('articles.category_id',$id)
                    ->where('users.status',1)
                    ->orderBy('articles.article_id','DESC')
                    ->paginate(5);
        // print_r($articles);
        // return;
        $category_model = new Category;
        $categories = $category_model->show();
        if(Auth::check())
            $my_article = Article::where('category_id',$id)->where('user_id',Auth::user()->user_id)->first();
        // return $my_article;
        //var_dump($categories);
        //$articles = $articles->toArray();
        return view('article_list',compact('categories','articles','category','i','my_article','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

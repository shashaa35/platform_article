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

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($category)
    {
        $category = Category::where('category_name',$category)->get();
        // var_dump($category);
        $category_id = $category[0]['category_id'];
        // echo $category_id;
        $status = "new";
        return view('editor',compact('status','category_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category_id = $request->category_id;
        $articles = Article::where([
                ['user_id','=',$request->user_id],
                ['category_id','=',$category_id]
            ])
            ->get();
        if(count($articles))
            return view('errors.503');
        $article = new Article;
        $article->user_id = Auth::user()->user_id;
        $article->category_id = $category_id;
        $article->title = $request->title;
        $article->content = $request->content;
        $article->reference = $request->reference;
        $article->avg_rating = -1;
        $article->no_of_rating = 0;
        $article->save();
        // return redirect('/editor/'.$article->article_id);
        return redirect('/editor/'.$article->article_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->user_id;
        }
        else
            $user_id = -1;
       $article = Article::where('article_id', $id)->get();
        if(!count($article))
            return view('errors.503');
        else
            $article = $article[0];
            $author = $article->user_id;
            //$author = (int)$author;
            $author = User::where('user_id',$author)->get();
            // var_dump($author);
            if($author[0]->status==0)
               return view('errors.503');
            $comments = Comment::join('users', 'users.user_id','=','comments.user_id')
                        ->select('users.username','comments.*')
                        ->where('comments.article_id', $id)
                        ->where('users.status',1)
                        ->get();

            $rating = Rating::where([
                ['article_id','=',$id],
                ['user_id','=',$user_id],
            ])->get();
            if(!count($rating))
                $rating_by_me=-1;
            else
                $rating_by_me = $rating[0]->rating;

            return view('article', compact('article','comments','user_id','rating_by_me'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->user_id;
            $article = Article::where([
                ['article_id','=',$id],
                ['user_id','=',$user_id],
            ])
            ->get(); 
            if(!count($article))
                return view('errors.503');
            else
            {
                return view('editor',compact('article'));
            }
        }
        else
            return view('errors.503');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            $user_id = $user->user_id;
            $article = Article::where([
                ['article_id','=',$request->article_id],
                ['user_id','=',$user_id],
            ]);
            if(!count($article))
                return view('errors.503');
            else
            {
                //update article
                $data = $request->only('title','content','reference');
                $article -> update($data);
                return redirect('/editor/'.$request->article_id);
            }
        }
        else
            return view('errors.503');
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

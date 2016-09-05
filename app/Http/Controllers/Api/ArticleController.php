<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Feed;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Helpers\Time;

class ArticleController extends ApiBaseController
{

    protected $type = Article::class;

    public function index() {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();

        if(Input::get('saved')) {
            return $this->getArticles(collect($user->articles()->get()));
        }
        else {
            return $this->getArticles(collect($this->getArticlesFromDB()));
        }
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
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        if (!$user->articles->contains($id)) {
            $user->articles()->attach($id);
            return ['status' => 'success'];
        }
        return ['status' => 'already saved'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        $user->articles()->detach($id);
        return ['status' => 'deleted'];
    }

    public function show(Request $request, $id) {
        return Article::find($id);
    }

    /**
     * Query to get the articles from db
     *
     * @return mixed
     */
    private function getArticlesFromDB() {
        if(isset($_GET['article-ids'])) {
            $ids = explode(',', $_GET['article-ids']);
            return Article::find($ids);
        }
        //return Article::limit(60)->orderBy('id', 'desc')->get();
        $where = isset($_GET['start']) ? ['articles.id', '<', $_GET['start']] : ['articles.id', '>', 0];
        $ids = explode(',', $_GET['ids']);
        return DB::table('articles')
            ->join('feeds', 'feed_id', '=', 'feeds.id')
            ->select('articles.feed_id', 'article_link', 'articles.created_at', 'articles.id', 'article_title', 'article_img',
                'article_description')
            ->whereIn('feeds.id', $ids)
            ->where($where[0], $where[1], $where[2])
            ->orderBy('articles.id', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Loop through articles and remove and format stuff
     *
     * @return mixed
     */
    public function getArticles($articles) {

        //dd($articles);

        $featuredChosen = false;
        $newArr = [];

        foreach($articles as $key => $article) {
            $des = $article->article_description;
            $article->article_description = \App\Helpers\HTML::stripTags($des);
            $article->article_title = html_entity_decode(htmlspecialchars_decode($article->article_title));
            $article->created_at = Time::utcToCentral($article->created_at);

            if($article->article_img && !$featuredChosen) {
                $article->featured = true;
                $featuredChosen = true;
                array_unshift($newArr, $article);
                unset($newArr[$key+1]);
            }
            else {
                $newArr[] = $article;
            }
        }

        return array_values($newArr);
    }
}
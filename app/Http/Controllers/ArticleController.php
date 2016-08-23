<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use App\Http\Requests;

class ArticleController extends Controller
{
    public function showArticle($id)
    {
        $article = Article::find($id);

        $article->article_description = \App\Helpers\HTML::stripTags($article->article_description);

        return view('pages.article', ['article' => $article]);
    }
}

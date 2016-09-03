<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class ArticleController extends ApiBaseController
{

    protected $type = Article::class;

    public function index() {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        return $user->articles;
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
        $user = Auth::user();
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
        $user = Auth::user();
        $user->articles()->detach($id);
        return ['status' => 'deleted'];
    }

    public function show(Request $request, $id) {
        return Article::find($id);
    }
}
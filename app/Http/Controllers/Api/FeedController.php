<?php

namespace App\Http\Controllers\Api;

use App\Feed;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class FeedController extends ApiBaseController
{

    protected $type = Feed::class;

    public function index() {
        $feeds = parent::index()->toArray();
        if(Auth::user()) {
            $activeFeeds = Auth::user()->feeds()->get();

            $ids = [];
            foreach($activeFeeds as $active) {
                $ids[] = $active->id;
            }
            //@todo revist all this with a cleaner method
            $updatedArr = [];
            foreach($feeds as $feed) {
                if($feed['id'])
                    if(in_array($feed['id'], $ids)) {
                        $feed['active'] = true;
                    }
                $updatedArr[] = $feed;
            }
            return $updatedArr;
        }
        return $feeds;
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

    /**
     * Updates user selected feeds
     *
     * @param Request $request
     * @return array
     */
    public function updateUserFeeds(Request $request) {
        $user = Auth::user();
        $user->feeds()->detach();
        $ids = [];
        foreach($request->all() as $r) {
            $ids[] = $r['id'];
        }
        $user->feeds()->attach($ids);
        return ['status', 'success'];
    }
}
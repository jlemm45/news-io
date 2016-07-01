<?php

namespace App\Http\Controllers\Api;

use App\Feed;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Http\Controllers\FeedController as Feeds;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class FeedController extends ApiBaseController
{

    protected $type = Feed::class;

    public function index() {
        $feeds = parent::index()->toArray();
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        if($user) {
            $activeFeeds = $user->feeds()->get();

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
        $f = new Feeds();

        $check = $f->checkIfFeedIsValid($request->feed_url);

        try{
            Feed::where('feed_url', '=', $request->feed_url)->firstOrFail();
            return ['status' => 'feed already added'];
        }
        catch(ModelNotFoundException $e) {
            if($check) {
                $feed = new Feed;
                $feed->feed_url = $request->feed_url;
                $feed->base_url = $f->getBaseUrl($request->feed_url);
                $feed->source = $check->get_title();
                $feed->save();
                Artisan::call('feeds:check'); //do an immediate check after a new feed is added
                return ['status' => 'success'];
            }
        }

        return ['status' => 'error'];
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
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        $user->feeds()->detach();
        $ids = [];
        foreach($request->all() as $r) {
            if(!empty($r['id'])) $ids[] = $r['id'];
        }
        $user->feeds()->attach($ids);
        return ['status', 'success'];
    }
}
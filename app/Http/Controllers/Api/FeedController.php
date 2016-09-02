<?php

namespace App\Http\Controllers\Api;

use App\Feed;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\Http\Controllers\FeedController as Feeds;
use App\Jobs\CrawlFeed;
use App\Http\Services\GoogleFeed;

class FeedController extends ApiBaseController
{

    protected $type = Feed::class;

    public function index() {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        if($user) {
            //$feeds = parent::index()->toArray();
            if(isset($_GET['term'])) {
                $term = $_GET['term'];
                return $this->filterByInactive(Feed::where('source', 'LIKE', '%'.$term.'%')->get());
            }
            else {
                return $user->unAddedFeeds()->paginate(10);
            }
        }
        return Feed::orderBy('id', 'asc')->take(8)->get();
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
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        $f = new Feeds();

        if(isset($request->feed_url)) {
            $check = $f->checkIfFeedIsValid($request->feed_url);

            try{
                Feed::where('feed_url', '=', $request->feed_url)->firstOrFail();
                return ['status' => 'feed already added'];
            }
            catch(ModelNotFoundException $e) {
                if($check) {
                    $feed = new Feed;
                    $feed->feed_url = $request->feed_url;
                    $feed->base_url = $f->getBaseUrl($check->get_permalink());
                    $feed->source = $check->get_title();
                    $feed->save();
                    $user->feeds()->attach($feed->id);
                    $this->dispatch(new CrawlFeed($feed)); //do an immediate check after a new feed is added
                    //Artisan::call('feeds:check');
                    return ['status' => 'success'];
                }
            }
        }
        else if(isset($request->feed_id)) {
            try {
                Feed::where('id', '=', $request->feed_id)->firstOrFail();
                if (!$user->feeds->contains($request->feed_id)) {
                    $user->feeds()->attach($request->feed_id);
                }
                else {
                    return ['status' => 'feed already added'];
                }
                return ['status' => 'success'];
            }
            catch(ModelNotFoundException $e) {
                return ['status' => 'error'];
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

    private function filterByActive($feeds) {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        $activeFeeds = $user->feeds()->get();

        $ids = [];
        foreach($activeFeeds as $active) {
            $ids[] = $active->id;
        }

        $updatedArr = [];
        foreach($feeds as $feed) {
            if($feed['id'])
                if(in_array($feed['id'], $ids)) {
                    $feed['active'] = true;
                    $updatedArr[] = $feed;
                }
            //$updatedArr[] = $feed;
        }
        return $updatedArr;
    }

    private function filterByInactive($feeds) {
        $user = Auth::user() ? Auth::user() : Auth::guard('api')->user();
        $activeFeeds = $user->feeds()->get();

        $ids = [];
        foreach($activeFeeds as $active) {
            $ids[] = $active->id;
        }

        $updatedArr = [];
        foreach($feeds as $feed) {
            if($feed['id'])
                if(!in_array($feed['id'], $ids)) {
                    $updatedArr[] = $feed;
                }
        }
        return $updatedArr;
    }

    /**
     * Method inactive for now. Not using google index for now.
     *
     * @param $term
     * @return array
     */
    private function searchGoogle($term) {

        $search = json_decode(GoogleFeed::query($term), true);

        $newArr = [];

        $feed = new Feeds();

        foreach($search['responseData']['entries'] as $item) {
            $newArr[] = ['source' => htmlspecialchars_decode(strip_tags($item['title'])), 'feed_url' => $item['url'],
                'id' => $item['url'], 'favicon_url' => GoogleFeed::favicon($feed->getBaseUrl($item['url']))];
        }

        return $newArr;
    }
}
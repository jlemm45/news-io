<!DOCTYPE html>
<html lang="en" ng-app="newsIO">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="News IO">
    <title>News IO</title>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body ng-controller="main">
<div class="ui thin sidebar visible" id="sidebar" ng-class="{'hidden': sidebarToggle}">
    <div id="toggle-wrap" ng-click="toggleSidebar()">
        <i class="sidebar icon"></i>
    </div>
    @foreach($feeds as $feed)
        <div class="item" ng-class="{'active': articleFilter == {{$feed->id}}}">
            <a href="#" ng-click="filterArticles({{$feed->id}})"><img src="https://s3-us-west-2.amazonaws.com/news-io/icons/{{$feed->icon_name}}.png"><b ng-show="!sidebarToggle"
                >{{$feed->source}}</b></a>
        </div>
    @endforeach
</div>
<div id="feed-stream" ng-class="{'wider': sidebarToggle}">
    <div class="ui text segment contain">
        <div class="ui grid">
            <div class="six wide column">
                <div class="featured-article article">
                    <a class="ui green ribbon label">The Latest</a>
                    <article article="feeds[0]"></article>
                </div>

                <div class="featured-article article">
                    <a class="ui green ribbon label">Featured</a>
                    <article article="feeds[1]"></article>
                </div>

            </div>
            <div class="ten wide column right">
                <div class="ui three column grid">
                    <div class="column" ng-repeat="feed in feeds" ng-if="$index > 0 && !articleFilter ||
                    articleFilter == feed.feed_id">
                        <div class="ui fluid card article">
                            <article article="feed"></article>
                        </div>
                    </div>
                </div>

                <button id="load-more" class="ui primary button" ng-click="getArticles(true)">
                    Load More
                </button>
            </div>
        </div>

        {{--<div class="ui left dividing rail">--}}
            {{--<div class="ui segment">--}}
                {{--@foreach($feeds as $feed)--}}
                {{--<div class="item">--}}
                {{--<a href="/"><b>{{$feed->source}}</b></a>--}}
                {{--</div>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular-sanitize.js"></script>
<script src="/js/app.js"></script>
<script src="/js/Directives/Article.js"></script>
<script src="/semantic/dist/semantic.min.js"></script>
</body>
</html>
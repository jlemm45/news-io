<!DOCTYPE html>
<html lang="en" ng-app="newsIO">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="News IO">
    <title>News IO</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body ng-controller="main">
{{--<div class="ui vertical inverted sticky menu">--}}
    {{--<div class="item">--}}
        {{--<a href="/"><b>News IO</b></a>--}}
    {{--</div>--}}
    {{--@foreach($feeds as $feed)--}}
        {{--<div class="item">--}}
            {{--<a href="/"><b>{{$feed->source}}</b></a>--}}
        {{--</div>--}}
    {{--@endforeach--}}
{{--</div>--}}
<div id="feed-stream">
    <div class="ui text container segment contain">
        <div class="ui left dividing rail">
            <div class="ui segment">
                @foreach($feeds as $feed)
                <div class="item">
                <a href="/"><b>{{$feed->source}}</b></a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="ui segment" ng-repeat="feed in feeds">
            <div class="icon">
                <img ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/@{{feed.feed_id}}.png">
            </div>
            <h2 class="ui header">@{{feed.article_title}}</h2>
            <img ng-src="@{{feed.article_img}}">
            <p>@{{feed.article_description}}</p>
            <p><a href="#">Read More</a></p>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
<script src="/js/app.js"></script>
</body>
</html>
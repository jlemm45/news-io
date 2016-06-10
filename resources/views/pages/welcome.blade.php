@extends('master')
@section('app', 'snug-welcome')

@section('content')
    <div ng-controller="welcomeController">
        <div id="header"></div>
        <div class="ui container">
            <div id="welcome-container">
                <div class="logo">
                    <img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-white.png">
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus mi ligula, maximus quis massa id, hendrerit gravida felis. Nam suscipit tempor ligula, quis ultrices est ullamcorper non.</p>

                <p>Select from the feeds below to build your customized news feed.</p>
                <div class="ui segment">
                    <div class="ui eight column grid">
                        <div class="column" ng-repeat="feed in feeds" ng-click="activateFeed(feed)"
                             ng-class="{'active': feed.active}">
                            <div class="overlay" ng-show="feed.active">
                                <i class="checkmark icon"></i>
                            </div>
                            <img ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/@{{feed.icon_name}}.png">
                        </div>
                    </div>
                </div>
                <button class="fluid ui green button" ng-click="submit()" ng-class="{'loading': loading}">Get
                    Started</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/Controllers/WelcomeController.js"></script>
    <script src="/js/Services/FeedService.js"></script>
@endsection
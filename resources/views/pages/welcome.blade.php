@extends('master')
@section('app', 'snug-welcome')

@section('content')
    <div ng-controller="welcomeController" id="welcome-page">
        <div id="header">
            <a class="ui inverted basic button right floated" href="/register">Register</a>
            <a class="ui inverted basic button right floated" href="/login">Login</a>
            <div class="inner-contain">
                <div class="logo">
                    <img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-white.png">
                </div>
            </div>
        </div>
        <div class="ui container">
            <div id="welcome-container">
                <div class="inner-contain">
                    <p>SnugFeed let's you stay up to date will all your favorite news sources in one place updated in real-time!</p>
                    <p>Select from some of our pre-existing feeds below to try it out.</p>
                    <p>Register and get access to add as many feeds and you want as well as your own custom feeds!</p>

                    <managefeedscomponent data="feedData"></managefeedscomponent>
                    <button class="fluid ui green button" ng-click="submit(feedData)" ng-class="{'loading': loading}" ng-disabled="disabled">Get Started</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(env('APP_ENV') == 'prod')
        <script src="{{env('CDN_URL')}}/js/welcome-bundle.js"></script>
    @else
    <script src="/js/Controllers/WelcomeController.js"></script>
    @endif
@endsection
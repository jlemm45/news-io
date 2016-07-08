@extends('master')

@section('content')
    <div id="header" class="small">
        <div class="ui container">
            <div class="ui large secondary inverted menu">
                <span class="logo">
                    <a href="/"><img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-white.png"></a>
                </span>
                @if (Auth::user())
                <a class="item" href="/feeds">Feeds</a>
                <a class="item" href="/me">Me</a>
                @else
                <a class="active item" href="/">Home</a>
                <a class="item" href="/register">Register</a>
                <a class="item" href="/login">Login</a>
                @endif
            </div>
        </div>
    </div>

    <div class="page">
        @yield('copy')
    </div>

    <div class="ui inverted vertical footer segment">
        <div class="ui container">
            <div class="ui stackable inverted divided equal height stackable grid">
                <div class="three wide column">
                    <h4 class="ui inverted header">Copyright 2016 SnugFeed</h4>
                </div>
                {{--<div class="seven wide column">--}}
                    {{--<h4 class="ui inverted header">Footer Header</h4>--}}
                    {{--<p>Extra space for a call to action inside the footer that could help re-engage users.</p>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endsection
@extends('master')

@section('content')
    <div class="ui bottom attached segment pushable" id="mobile-segment">
        <div class="ui inverted labeled icon left inline vertical sidebar menu" id="mobile-menu">
            @if (Auth::user())
                <a class="item" href="/feeds">Feeds</a>
                <a class="item" href="/me">Me</a>
            @else
                <a class="active item" href="/">Home</a>
                <a class="item" href="/register">Register</a>
                <a class="item" href="/login">Login</a>
            @endif
        </div>
        <div class="pusher">
            <div id="header" class="small">
                <div class="ui container">
                    <div class="ui large secondary inverted menu">
                    <span class="logo">
                        <a href="/"><img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-white.png"></a>
                    </span>
                        @if (Auth::user())
                            <a class="item mobile-hide" href="/feeds">Feeds</a>
                            <a class="item mobile-hide" href="/me">Me</a>
                        @else
                            <a class="active item mobile-hide" href="/">Home</a>
                            <a class="item mobile-hide" href="/register">Register</a>
                            <a class="item mobile-hide" href="/login">Login</a>
                        @endif
                        <a class="item mobile-show right floated" id="toggle-mobile">
                            <i class="sidebar icon"></i>
                            Menu
                        </a>
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
        </div>
    </div>
@endsection

@section('scripts')
<script src="/js/MobileNav.js"></script>
@yield('scripts2')
@endsection
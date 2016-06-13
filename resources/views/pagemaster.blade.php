@extends('master')

@section('content')
    <div id="header" class="small">
        <div class="ui container">
            <div class="ui large secondary inverted menu">
                <span class="logo">
                    <a href="/"><img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-white.png"></a>
                </span>
                <a class="active item" href="/">Home</a>
                <a class="item" href="/register">Register</a>
                <a class="item" href="/login">Login</a>
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
                    <h4 class="ui inverted header">About</h4>
                    <div class="ui inverted link list">
                        <a href="#" class="item">Sitemap</a>
                        <a href="#" class="item">Contact Us</a>
                        <a href="#" class="item">Religious Ceremonies</a>
                        <a href="#" class="item">Gazebo Plans</a>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui inverted header">Services</h4>
                    <div class="ui inverted link list">
                        <a href="#" class="item">Banana Pre-Order</a>
                        <a href="#" class="item">DNA FAQ</a>
                        <a href="#" class="item">How To Access</a>
                        <a href="#" class="item">Favorite X-Men</a>
                    </div>
                </div>
                <div class="seven wide column">
                    <h4 class="ui inverted header">Footer Header</h4>
                    <p>Extra space for a call to action inside the footer that could help re-engage users.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
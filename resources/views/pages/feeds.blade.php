@extends('master')
@section('app', 'snug-feeds')

@section('content')
    <div ng-controller="feedsController">
        <div id="incoming" ng-class="{'active': incoming}">
            <div class="ui container">
                <i class="notched circle loading icon"></i>
                <p>Incoming Article</p>
            </div>
        </div>
        <div class="ui thin sidebar visible" id="sidebar" ng-class="{'hidden': sidebarToggle}">
            <div class="toggle" ng-click="toggleSidebar()">
                <i class="sidebar icon"></i>
            </div>
            <div id="logo">
                <a href="/">
                    <img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-green.png" class="img-responsive">
                </a>
            </div>
            <div class="item" ng-class="{'active': !articleFilter && !showSaved}">
                <a href="#" ng-click="filterArticles(false)">All</a>
            </div>
            <div class="item" ng-class="{'active': articleFilter == feed.id && !showSaved}" ng-repeat="feed in
            activeFeeds">
                <a href="#" ng-click="filterArticles(feed.id)"><img ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/@{{feed.icon_name}}.png"><b ng-show="!sidebarToggle">@{{feed.source}}</b></a>
            </div>
        </div>
        <div id="feed-stream" ng-class="{'wider': sidebarToggle}">
            {{--<div class="new-articles">--}}
                {{--New Articles--}}
            {{--</div>--}}
            <div class="ui text segment contain">
                {{--<div class="ui icon message" ng-show="incoming">--}}
                    {{--<i class="notched circle loading icon"></i>--}}
                    {{--<div class="content">--}}
                        {{--<div class="header">--}}
                            {{--Alert--}}
                        {{--</div>--}}
                        {{--<p>Incoming Article</p>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="ui warning message" ng-if="!user">
                    <i class="close icon" ng-click="warning=true;"></i>
                    <div class="header">
                        FYI
                    </div>
                    <p>Don't forget to register to save your feeds.</p>
                </div>
                <div class="">
                    {{--<div class="six wide column">--}}
                        {{--<div class="featured-article article" ng-class="{'incoming': feeds[0].incoming}">--}}
                            {{--<a class="ui green ribbon label">The Latest</a>--}}
                            {{--<article article="feeds[0]"></article>--}}
                        {{--</div>--}}

                        {{--<div class="featured-article article" ng-class="{'incoming': feeds[1].incoming}">--}}
                            {{--<a class="ui green ribbon label">Featured</a>--}}
                            {{--<article article="feeds[1]"></article>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div id="article-contain">
                        <div class="mason-sizer"></div>
                        <div class="mason-gutter"></div>
                        <div class="mason" ng-repeat="feed in feeds" ng-if="!articleFilter ||
                        articleFilter == feed.feed_id">
                            <div class="ui fluid card article" ng-class="{'incoming': feed.incoming}">
                                <article article="feed" view="true"></article>
                            </div>
                        </div>
                        {{--<div class="mason-parent" ng-if="articleView">--}}
                            {{----}}
                        {{--</div>--}}

                        <div class="ui one column grid" ng-if="!articleView">
                            <div class="column" ng-repeat="feed in feeds" ng-if="!articleFilter ||
                        articleFilter == feed.feed_id">
                                <div class="ui fluid card article" ng-class="{'incoming': feed.incoming}">
                                    <article article="feed" view="false"></article>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button ng-if="!showSaved" id="load-more" class="ui primary button"
                            ng-click="getArticles
                        (true)">
                        Load More
                    </button>
                </div>
            </div>
        </div>
        <div id="utility-bar">
            <div ng-if="user" class="ui grid">
                <div class="twelve wide column">
                    <div id="user-manage-menu">
                        <i class="settings icon"></i>
                        <div class="avatar">@{{user.initials}}</div><span class="name">@{{user.name}}</span>
                        <div class="popout">
                            <span ng-click="showMangeFeedsModal()"><i class="configure icon"></i>Manage Feeds</span>
                        </div>
                    </div>
                    <div class="line pointer" ng-click="showSavedArticles()">
                        <i class="archive icon"></i>
                        <span>@{{savedArticles.length}} Saved Articles</span>
                    </div>
                </div>
                <div class="four wide column">
                    <div class="line">
                        <span>List View</span>
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="public" ng-change="toggleView(toggle)" ng-model="toggle"
                                   ng-init="toggle=true">
                            <label></label>
                        </div>
                        <span>Grid View</span>
                    </div>
                    <a class="ui button right floated" href="/auth/logout">Logout</a>
                </div>
            </div>
            <div ng-if="!user" class="ui grid">
                <div class="sixteen wide column">
                    <button class="ui button right floated" ng-click="showLoginModal()">Login</button>
                    <button class="ui button right floated">Register</button>
                </div>
            </div>
        </div>
        <modal options="loginModal"></modal>
        <modal options="feedsModal"></modal>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
    <script src="//npmcdn.com/masonry-layout@4.0.0/dist/masonry.pkgd.min.js"></script>
    <script src="/js/Controllers/FeedsController.js"></script>
    <script src="/js/Directives/Article.js"></script>
    <script src="/js/Services/ArticleService.js"></script>
@endsection
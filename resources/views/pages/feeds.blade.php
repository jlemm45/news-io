@extends('master')
@section('app', 'snug-feeds')

@section('content')
    <div ng-controller="feedsController">
        <div id="incoming" ng-class="{'active': incoming}" ng-cloak>
            <div class="ui container">
                <i class="notched circle loading icon"></i>
                <p>@{{noti.text}}</p>
            </div>
        </div>
        <div class="ui thin sidebar visible" id="sidebar" ng-class="{'hidden': sidebarToggle}">
            <div class="toggle" ng-click="toggleSidebar()">
                <i class="sidebar icon"></i>
            </div>
            <div id="logo">
                <a href="/">
                    <img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-green.png" class="img-responsive"
                         ng-if="!sidebarToggle">
                    <img src="https://s3-us-west-2.amazonaws.com/news-io/img/logo-green-abb.png"
                         class="img-responsive" ng-if="sidebarToggle">
                </a>
            </div>
            <div class="item" ng-class="{'active': !articleFilter && !showSaved}">
                <a href="#" ng-click="filterArticles(false)">All</a>
            </div>
            <div class="item" ng-class="{'active': articleFilter == feed.id && !showSaved}" ng-repeat="feed in
            activeFeeds">
                <a href="#" ng-click="filterArticles(feed.id)"><img ng-if="feed.icon_name" ng-src="https://s3-us-west-2.amazonaws.com/news-io/icons/@{{feed.icon_name}}.png"><b ng-show="!sidebarToggle">@{{feed.source}}</b></a>
            </div>
        </div>
        <div id="feed-stream" ng-class="{'wider': sidebarToggle}" ng-cloak>
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
                        <div ng-if="!articleView">
                            <div class="mason-sizer"></div>
                            <div class="mason-gutter"></div>
                            <div class="mason-featured"></div>

                            <div class="mason" ng-repeat="feed in feeds" ng-if="!articleFilter ||
                        articleFilter == feed.feed_id || showSaved" ng-class="{'featured': feed.featured}">
                                <div class="ui fluid card article" ng-class="{'incoming': feed.incoming}">
                                    <article article="feed" view="true" showsaved="showSaved"></article>
                                </div>
                            </div>
                        </div>

                        <div ng-if="articleView">
                            <div ng-repeat="feed in feeds" class="list-article" ng-if="!articleFilter || articleFilter
                             == feed.feed_id || showSaved">
                                <article article="feed" view="false" showsaved="showSaved"></article>
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
                            <div ng-click="showMangeFeedsModal()"><i class="configure icon"></i>Manage Feeds</div>
                            <div ng-click="showNewFeedModal()"><i class="plus icon"></i>Add New Feed</div>
                        </div>
                    </div>
                    <div class="line pointer" ng-click="showSavedArticles()">
                        <i class="archive icon"></i>
                        <span>@{{savedArticles.length}} Saved Articles</span>
                    </div>
                </div>
                <div class="four wide column">
                    <div class="line">
                        <span>Grid View</span>
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="public" ng-change="toggleView(toggle)" ng-model="toggle"
                                   ng-init="toggle=articleView">
                            <label></label>
                        </div>
                        <span>List View</span>
                    </div>
                    <a class="ui button right floated" href="/auth/logout">Logout</a>
                </div>
            </div>
            <div ng-if="!user" class="ui grid">
                <div class="sixteen wide column">
                    <button class="ui button right floated" ng-click="showLoginModal()">Login/Register</button>
                </div>
            </div>
        </div>
        <modal template="login" header="Login" modal-id="loginModal" options="loginModal"></modal>
        <modal template="manageFeeds" header="Manage Feeds" modal-id="feedsModal" options="manageFeedsModal"></modal>
        <modal header="Add New Feed" modal-id="newFeedModal">
            <p>Feed must be a valid xml rss feed.</p>
            <newfeedcomponent></newfeedcomponent>
        </modal>
        <modal header="@{{articleToRead.article_title}}" modal-id="readArticleModal">
            <readarticlecomponent article="articleToRead"></readarticlecomponent>
        </modal>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
    <script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
    <script src="//npmcdn.com/masonry-layout@4.0.0/dist/masonry.pkgd.min.js"></script>
    <script src="/js/Controllers/FeedsController.js"></script>
    <script src="/js/Directives/Article.js"></script>
    <script src="/js/Directives/NewFeed.js"></script>
    <script src="/js/Services/ArticleService.js"></script>
    <script src="/js/Directives/ReadArticle.js"></script>
    <script src="/js/Services/PreferenceService.js"></script>
@endsection
@extends('master')
@section('app', 'snug-feeds')

@section('content')
    <div ng-controller="feedsController" ng-click="clearClick()" id="page-contain">
        <div id="page-loading" class="ui active inverted dimmer" ng-if="loading">
            <div class="ui large text loader">Loading</div>
        </div>
        <div id="incoming" ng-class="{'active': incoming}" ng-cloak>
            <div class="ui container">
                <i class="notched circle loading icon"></i>
                <p>@{{noti.text}}</p>
            </div>
        </div>
        <div id="feed-stream" ng-class="{'wider': sidebarToggle}" class="ui container" ng-cloak>
            <div class="sidebar-wrap">
                <div class="ui thin sidebar visible" id="sidebar" top-scroll>
                    <div id="logo">
                        <a href="/">
                            <img src="https://s3-us-west-2.amazonaws.com/news-io/img/snug-logo.svg" class="img-responsive"
                                 ng-if="!sidebarToggle">
                            <img src="https://s3-us-west-2.amazonaws.com/news-io/img/snug-logo-abbrv.svg"
                                 class="img-responsive" ng-if="sidebarToggle">
                        </a>
                    </div>
                    <div>
                        <div ng-if="!emptyFeeds" class="item" ng-class="{'active': !articleFilter && !showSaved}">
                            <a href="#" ng-click="filterArticles(false)">All</a>
                        </div>
                        <div class="item" ng-class="{'active': articleFilter == feed.id && !showSaved}" ng-repeat="feed in
            activeFeeds">
                            <a href="#" ng-click="filterArticles(feed.id)"><img ng-src="@{{feed.favicon_url}}"><b ng-show="!sidebarToggle">@{{feed.source}}</b></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="articles">
                <div class="ui text segment contain">
                    <div id="no-feeds" ng-if="emptyFeeds">
                        <h1>Oh No!</h1>
                        <img src="https://s3-us-west-2.amazonaws.com/news-io/img/crying-no-feeds.png">
                        <p>Looks like you haven't added any feeds yet!</p>
                    </div>
                    <div class="ui message" ng-if="!user && !showNotice">
                        <i class="close icon" ng-click="hideNotice()"></i>
                        <div class="content">
                            <div class="header">
                                Notice
                            </div>
                            <p>Don't forget to register to save your feeds.</p>
                        </div>
                        <button class="ui button">Register</button>
                    </div>
                    <div class="">
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
                        <button ng-if="!showSaved && !emptyFeeds" id="load-more" class="ui primary button"
                                ng-click="getArticles
                        (true)">
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="show-feeds-arrow" ng-if="emptyFeeds" ng-cloak>
            <i class="arrow circle left icon"></i>
        </div>

        <div class="popout" ng-show="showSettingsMenu" ng-cloak>
            <div ng-click="showMangeFeedsModal()"><i class="configure icon"></i>Manage Feeds</div>
            <div ng-click="showNewFeedModal()"><i class="plus icon"></i>Add New Feed</div>
        </div>
        <div id="utility-bar">
            <div ng-if="user" ng-cloak>
                <div class="left">
                    <div id="user-manage-menu">
                        <i class="setting icon" ng-click="toggleSettingsMenu($event)"></i>
                    </div>
                    <div class="line pointer" ng-click="showSavedArticles()">
                        <i class="archive icon"></i>
                        <span>@{{savedArticles.length}} Saved Articles</span>
                    </div>
                </div>
                <div class="right">
                    <div class="line right floated">
                        <span>Grid View</span>
                        <div class="ui slider checkbox">
                            <input type="checkbox" name="public" ng-change="toggleView(toggle)" ng-model="toggle"
                                   ng-init="toggle=articleView">
                            <label></label>
                        </div>
                        <span>List View</span>
                    </div>
                    <div class="ui left labeled button right floated" id="logout-avatar">
                        <a class="ui basic right pointing label" href="/me">
                            <div class="avatar">@{{user.initials}}</div><span class="name">@{{user.name}}</span>
                        </a>
                        <a href="/auth/logout" class="ui button">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
            <div ng-if="!user" class="ui grid">
                <div class="sixteen wide column">
                    <button class="ui button right floated" ng-click="showLoginModal()">Login/Register</button>
                </div>
            </div>
        </div>
        <modal template="login" header="Login" modal-id="loginModal" options="loginModal"></modal>
        <modal template="manageFeeds" header="Manage Feeds" modal-id="feedsModal" options="manageFeedsModal" ng-if="user"></modal>
        <modal header="Add New Feed" modal-id="newFeedModal" ng-if="user">
            <p>Add a feed from our index of feeds or add any valid xml rss feed by url.</p>
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
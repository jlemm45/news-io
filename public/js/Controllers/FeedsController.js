'use strict';

var snugfeeds = angular.module('snug-feeds', ['article', 'snugfeed.service.articles', 'ngCookies', 'modal', 'logincomponent', 'registercomponent', 'snugfeed.service.user', 'managefeedscomponent', 'snugfeed.service.feeds']);

/**
 * Feeds Controller
 */
snugfeeds.controller('feedsController', function($scope,$http,snugfeedArticlesService,$cookies,snugfeedUserService,snugfeedFeedsService,$timeout) {

    $scope.feeds = [];                                  //all active articles
    $scope.lastFeedID = 43;                             //last article id for loading more articles
    $scope.sidebarToggle = false;                       //toggles state of sidebar
    $scope.articleFilter = false;                       //toggles filtering articles
    $scope.loginModal = {
        title: 'Login',                                 //title of modal
        id: 'loginModal',                               //id of modal
        show: null,                                     //show the modal
        hide: null,                                     //hide the modal
        template: 'login',                              //modal template
        activeModel: 'login',                           //login or register
        success: function(data) {                       //callback called on success of login component
            getUserStatus();
            $scope.loginModal.hide();
        }
    };
    $scope.feedsModal = {
        title: 'Manage Feeds',
        id: 'feedsModal',
        show: null,
        hide: null,
        template: 'manageFeeds',
        saveFeeds: function(feeds) {
            handleFeedUpdate(feeds);
        }
    };
    $scope.user = false;                                //holds active user info
    $scope.activeFeeds = $cookies.getObject('feeds');   //active feeds
    $scope.savedArticles = {};                          //user saved articles
    $scope.showSaved = false;                           //if we are showing saved articles
    $scope.articleView = true;                          //handles toggling view to list or grid

    var msnry = new Masonry( '#article-contain', {      //msnry layout
        itemSelector: '.mason',
        columnWidth: '.mason-sizer',
        percentPosition: true,
        gutter: '.mason-gutter'
    });

    function resetLayout() {
        $timeout(function() {
            msnry.reloadItems();
            msnry.layout();
        },200);
    }

    /**
     * Get's all articles saved by the user
     */
    function getSavedArticles() {
        snugfeedArticlesService.getSavedArticles().then(function(resp) {
           $scope.savedArticles = resp.data;
        });
    }

    /**
     * Returns feed id's from cookie
     * @returns {*|Array}
     */
    function getFeedsIds() {
        var feeds = $scope.user.feeds ? $scope.user.feeds : $scope.activeFeeds;
        return feeds.map(function(i) {
            return i.id;
        });
    }

    /**
     * Subscribe logged in user to sockets
     */
    function subscribeToSockets() {
        socket.emit('subscribe', {
            userID: $scope.user.id,
            feeds: getFeedsIds()
        });
    }

    /**
     * Get's current user status as well as selected feeds
     */
    function getUserStatus() {
        snugfeedUserService.getUserStatus().then(function(data) {
            if(data.data.user) {
                $scope.user = data.data.user;
                $scope.activeFeeds = $scope.user.feeds;
                $scope.user.initials = snug.generateAvatarInitials($scope.user.name);
                subscribeToSockets();
            }
            $scope.getArticles(false);
        });
    }

    /**
     * Update user active feeds via feed service
     * @param feeds
     */
    function handleFeedUpdate(feeds) {
        feeds = feeds.filter(function(i) {
            if(i.active) return i;
        });

        snugfeedFeedsService.updateFeeds(feeds).then(function(data) {
            getUserStatus();
            $scope.feedsModal.hide();
        });
    }

    /**
     * Get articles based on selected articles in cookie
     * @param page
     */
    $scope.getArticles = function(page) {
        if(!page) $scope.feeds = [];
        page = page ? $scope.lastFeedID : false;
        var ids = $scope.articleFilter ? [$scope.articleFilter] : getFeedsIds();
        snugfeedArticlesService.getArticles(page,ids).then(function(data) {
            $scope.feeds = $scope.feeds.concat(data.data);
            $scope.lastFeedID = data.data[data.data.length - 1].id;

            resetLayout();

        });
    };

    /**
     * Toggles sidebar state
     */
    $scope.toggleSidebar = function() {
        $scope.sidebarToggle = $scope.sidebarToggle ? false : true;
    };

    /**
     * Filters articles
     * @param id
     */
    $scope.filterArticles = function(id) {
        $scope.articleFilter = id;
        $scope.getArticles(false);
        $scope.showSaved = false;
    };

    /**
     * Shows the login modal
     */
    $scope.showLoginModal = function() {
        $scope.loginModal.activeModel = 'login';
        $scope.loginModal.show();
    };

    /**
     * Shows the manage feed modal
     */
    $scope.showMangeFeedsModal = function() {
        $scope.feedsModal.show();
    };

    /**
     * Change view to saved articles only
     */
    $scope.showSavedArticles = function() {
        $scope.feeds = $scope.savedArticles;
        $scope.showSaved = true;
    };

    //on load
    getUserStatus();
    getSavedArticles();

    function incoming() {
        $scope.incoming = true;
        setTimeout(function() {
            $scope.incoming = false;
            $scope.$apply();
        },3000);
        $scope.$apply();
    }

    //socket connection
    var socket = io.connect('http://vagrant.local:8890/', {
        reconnection: false
    });

    socket.on('article', function(json) {
        handleNewArticles(json);
    });

    /**
     * Handle new incoming articles. Only use articles in which feeds users are listening to.
     * @param json
     */
    function handleNewArticles(json) {
        var userFeedIds = getFeedsIds();
        var idsToGet = [];
        $.each(json[0], function(feedID,articleArr) {
            if(userFeedIds.indexOf(parseInt(feedID)) > -1) {
                $.each(articleArr, function(k,articleID) {
                    idsToGet.push(articleID);
                    $scope.feeds.unshift({incoming: true});
                });
            }
            resetLayout();
        });
        console.log(idsToGet);
        if(idsToGet.length > 0) {
            incoming();
            snugfeedArticlesService.getArticlesByIds(idsToGet.join()).then(function(resp) {
                var length = resp.data.length - 1;
                $.each(resp.data, function(k,v) {
                    bringInNewArticle(length, v);
                    //$scope.feeds.unshift(v);
                    length--;
                });
            });
        }
    }

    function bringInNewArticle(index, article) {
        $timeout(function() {
            console.log(index);
            $scope.feeds[index] = article;
            resetLayout();
        },3000*(index+1));
    }

    $scope.toggleView = function(toggle) {
        $scope.articleView = toggle;
        //console.log(toggle);
    }
});
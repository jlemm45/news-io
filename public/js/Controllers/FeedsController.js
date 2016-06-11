'use strict';

var snugfeeds = angular.module('snug-feeds', ['article', 'snugfeed.service.articles', 'ngCookies', 'modal', 'logincomponent', 'registercomponent', 'snugfeed.service.user', 'managefeedscomponent', 'snugfeed.service.feeds']);

/**
 * Feeds Controller
 */
snugfeeds.controller('feedsController', function($scope,$http,snugfeedArticlesService,$cookies,snugfeedUserService,snugfeedFeedsService) {

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
     * Get's current user status as well as selected feeds
     */
    function getUserStatus() {
        snugfeedUserService.getUserStatus().then(function(data) {
            if(data.data.user) {
                $scope.user = data.data.user;
                $scope.activeFeeds = $scope.user.feeds;
                $scope.user.initials = snug.generateAvatarInitials($scope.user.name);
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
        snugfeedArticlesService.getArticles(page,getFeedsIds()).then(function(data) {
            $scope.feeds = $scope.feeds.concat(data.data);
            $scope.lastFeedID = data.data[data.data.length - 1].id;
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
    };

    //on load
    getUserStatus();
    getSavedArticles();
});
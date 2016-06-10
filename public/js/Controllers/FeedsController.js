'use strict';

var snugfeeds = angular.module('snug-feeds', ['article', 'snugfeed.service.articles', 'ngCookies', 'modal', 'logincomponent']);

/**
 * Feeds Controller
 */
snugfeeds.controller('feedsController', function($scope,$http,snugfeedArticlesService,$cookies) {

    $scope.activeFeeds = $cookies.getObject('feeds');   //active feeds in cookie
    $scope.feeds = [];                                  //all active articles
    $scope.lastFeedID = 43;                             //last article id for loading more articles
    $scope.sidebarToggle = false;                       //toggles state of sidebar
    $scope.articleFilter = false;                       //toggles filtering articles
    $scope.loginModal = {
        title: 'Login',
        id: 'loginModal',
        show: null,
        template: 'login'
    };

    /**
     * Returns feed id's from cookie
     * @returns {*|Array}
     */
    function getCookieFeeds() {
        return $scope.activeFeeds.map(function(i) {
            return i.id;
        });
    }

    /**
     * Get articles based on selected articles in cookie
     * @param page
     */
    $scope.getArticles = function(page) {
        var ids = getCookieFeeds();
        page = page ? $scope.lastFeedID : false;
        snugfeedArticlesService.getArticles(page,ids).then(function(data) {
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

    $scope.showLoginModal = function() {
        $scope.loginModal.show();
    };

    //on load
    $scope.getArticles(false);
});
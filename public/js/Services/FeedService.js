'use strict';

(function(angular) {
    angular.module('snugfeed.service.feeds', [])
        .service('snugfeedFeedsService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

            var getFeeds = function () {
                return $http.get("/api/feed");
            };

            var getActiveFeeds = function() {
                return $http.get("/api/feed?active");
            };

            var addFeed = function (data) {
                return $http.post("/api/feed", data);
            };

            var removeFeed = function (id) {
                return $http.delete("/api/feed/"+id);
            };

            var updateFeeds = function (feeds) {
                return $http.put("/api/feeds", feeds);
            };

            var searchForFeed = function(term) {
                return $http.get("/api/feed?term="+term);
            };

            return {
                getFeeds: getFeeds,
                updateFeeds: updateFeeds,
                addFeed: addFeed,
                getActiveFeeds: getActiveFeeds,
                removeFeed: removeFeed,
                searchForFeed: searchForFeed
            };

        }]);
})(angular);
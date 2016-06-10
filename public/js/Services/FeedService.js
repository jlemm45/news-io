angular.module('snugfeed.service.feeds', [])
    .service('snugfeedFeedsService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

        var getFeeds = function () {
            return $http.get("/api/feed");
        };

        return {
            getFeeds: getFeeds
        };

    }]);
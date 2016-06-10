angular.module('snugfeed.service.articles', [])
    .service('snugfeedArticlesService', [ "$http", "$q", "$rootScope", function ($http, $q, $rootScope) {

        var getArticles = function (page,ids) {
            var query = page ? '&start='+page : '';
            ids = '?ids='+ids.join();
            return $http.get("/api/articles"+ids+query);
        };

        return {
            getArticles: getArticles
        };

    }]);
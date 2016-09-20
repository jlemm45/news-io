'use strict';

(function(angular) {
    angular.module('snugfeed.service.articles', [])
        .service('snugfeedArticlesService', function ($http) {

            return {
                getArticles: function (page,ids) {
                    var query = page ? '&start='+page : '';
                    ids = '?ids='+ids.join();
                    return $http.get("/api/articles"+ids+query);
                },
                saveArticle: function(id) {
                    return $http.put("/api/article/"+id);
                },

                deleteArticle: function(id) {
                    return $http.delete("/api/article/"+id);
                },
                getArticle: function(id) {
                    return $http.get("/api/article/"+id);
                },
                getArticlesByIds: function(ids) {
                    return $http.get("/api/articles?article-ids="+ids);
                },
                getSavedArticles: function() {
                    return $http.get("/api/articles?saved=true");
                }
            };

        });
})(angular);
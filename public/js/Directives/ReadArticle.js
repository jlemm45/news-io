'use strict';

(function(angular) {
    function readArticleController($scope, $element, $attrs, $sce) {
        $scope.toTrustedHTML = function( html ){
            return $sce.trustAsHtml( html );
        };

        console.log($scope);
    }

    angular.module('readarticlecomponent', ['ngSanitize']).component('readarticlecomponent', {
        controller: readArticleController,
        bindings: {article: '='},
        template: '' +
        '<p ng-bind-html="toTrustedHTML($ctrl.article.article_description)"></p>'
    });
})(angular);

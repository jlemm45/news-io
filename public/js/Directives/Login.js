angular.module('logincomponent', ['snugfeed.service.user']).directive('logincomponent', function($sce,snugfeedUserService) {

    function link(scope, element, attrs) {
        scope.loginUser = function(login) {
            snugfeedUserService.loginUser(login).then(function(data) {
                console.log('test');
            });
        }
    }

    return {
        link: link,
        restrict: 'E',
        scope: {},
        templateUrl: '/js/templates/components/login.html'
    };
});
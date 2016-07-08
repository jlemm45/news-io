'use strict';

(function(angular) {
    angular.module('logincomponent', ['snugfeed.service.user']).directive('logincomponent', function($sce,snugfeedUserService) {

        function validate() {
            $('#login-form')
                .form({
                    fields: {
                        email     : 'empty',
                        password : ['minLength[6]', 'empty']
                    }
                });
        }

        function link(scope, element, attrs) {
            scope.loading = false;
            validate();
            scope.submit = function($event,login) {
                $event.preventDefault();
                if($('#login-form').form('is valid'))
                    scope.loading = true;
                    snugfeedUserService.loginUser(login).then(function(resp) {
                        scope.$emit('login success', resp.data); //emit to parents
                    },function(error) {
                        scope.loading = false;
                        $('#login-form').form('add errors', ['Invalid email or password']);
                    });
            }
        }

        return {
            link: link,
            restrict: 'E',
            scope: {success: '=success'},
            templateUrl: '/js/templates/components/login.html'
        };
    });
})(angular);
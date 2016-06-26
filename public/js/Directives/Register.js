'use strict';

(function(angular) {
    angular.module('registercomponent', ['snugfeed.service.user']).directive('registercomponent', function($sce,snugfeedUserService) {

        function validate() {
            $('#register-form')
                .form({
                    fields: {
                        email     : 'empty',
                        name     : 'empty',
                        password : ['minLength[6]', 'empty'],
                        password_confirmation : ['minLength[6]', 'empty']
                    }
                });
        }

        function link(scope, element, attrs) {
            validate();
            scope.registerUser = function($event,register) {
                $event.preventDefault();
                if($('#register-form').form('is valid'))
                    snugfeedUserService.registerUser(register).then(function(resp) {
                        scope.$emit('register success', resp.data); //emit to parents
                    },function(error) {
                        $('#register-form').form('add errors', error.data.errors);
                    });
            }
        }

        return {
            link: link,
            restrict: 'E',
            scope: {success: '=success'},
            templateUrl: '/js/templates/components/register.html'
        };
    });
})(angular);

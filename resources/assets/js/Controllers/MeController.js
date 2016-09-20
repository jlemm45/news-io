'use strict';

(function(angular) {
    var snugme = angular.module('snug-me', ['snugfeed.service.user']);

    /**
     * Me Controller
     */
    snugme.controller('meController', function($scope,snugfeedUserService) {

        $scope.loading = false;
        $scope.passwordChanged = false;

        function validate() {
            $('#password-change-form')
                .form({
                    fields: {
                        password : ['minLength[6]', 'empty'],
                        password_confirm : ['minLength[6]', 'empty']
                    }
                });
        }
        validate();

        $scope.submit = function($event,form) {
            $event.preventDefault();
            if($('#password-change-form').form('is valid'))
                $scope.loading = true;
                snugfeedUserService.updatePassword(form).then(function(resp) {
                    if(resp.data.status == 'success')
                        $scope.passwordChanged = true;
                        $scope.loading = false;
                        $scope.password = {};
            },function(error) {
                $scope.loading = false;
                $('#login-form').form('add errors', ['There was an error']);
            });
        }

    });
})(angular);
app.controller('loginController', ['$scope', '$location', '$rootScope',
  '$uibModal', 'services', 'steps',
  function($scope, $location, $rootScope, $uibModal, services, steps) {

    $scope.user = {};

    $scope.login = function() {
      services.login($scope.user).then(function(data) {
        if (data.data) { //user exists
          $rootScope.user =  $scope.user = data.data;
          $rootScope.userLoggedIn = true;
          $location.path('/requirements');
             steps.chain = {step: {}, requirement: {}, functionality: {}, example: {}};
          steps.chain.step = 1;
        }
      });
    };

    //   $scope.forgotPassword = function (user)
    //   {
    //      user.password = '';
    //      $modal.open({
    //         templateUrl: baseUrl + 'views/login/forgot_password.html',
    //         controller: 'forgotPasswordController',
    //         scope: $scope
    //      })
    //      .result.then(function () { $scope.$close(); });
    //   };
    //    $scope.close = function () {
    //        $scope.modal.close();
    //    };

    $scope.register = function() {

      $scope.item = {};

      return ($uibModal.open({
          templateUrl: './views/login/register.html',
          scope: $scope
        })
        .result.then(function() {
          // console.dir($scope.item);
          $scope.item['userType'] = 1;

          services.addNewUser($scope.item).then(function(data) {
            // console.log(data);
          });
        }));
    };
  }
]);

//app.controller('forgotPasswordController', ['$scope', '$modal', 'server',  function ($scope, $modal, server)
//{
//   $scope.confirm = function ()
//   {
//      server.call('login', 'do_reset_password', { userName: $scope.user.userName }, true)
//      .then(function (data) {
//         $modal.open({
//            templateUrl: baseUrl + 'views/login/reset_password_success.html',
//            scope: $scope
//         })
//         .result.then(function () { $scope.$close(); });
//      },
//      function (data) {
//         $modal.open({
//            templateUrl: baseUrl + 'views/login/reset_password_failure.html',
//            scope: $scope
//         });
//      });
//   };
//}]);

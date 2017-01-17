app.controller('loginController', ['$scope', '$location', '$rootScope', '$routeSegment', function ($scope, $location, $rootScope, $routeSegment)
{
    
    $scope.login = function () {
      $rootScope.user = true;  //for dev purposes only, should do actual login here
        $location.path('/start/requirements');
         $scope.modal.close();
    };
    
    
//   $scope.login = function (user)
//   {
//      server.login(user.userName, user.password).then(function () {
//         window.location.reload();
//      });
//   };
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
    
    $scope.close = function () {
        $scope.modal.close();
    };
}]);

app.controller('forgotPasswordController', ['$scope', '$modal', 'server',  function ($scope, $modal, server)
{
   $scope.confirm = function ()
   {
      server.call('login', 'do_reset_password', { userName: $scope.user.userName }, true)
      .then(function (data) {
         $modal.open({
            templateUrl: baseUrl + 'views/login/reset_password_success.html',
            scope: $scope
         })
         .result.then(function () { $scope.$close(); });
      },
      function (data) {
         $modal.open({
            templateUrl: baseUrl + 'views/login/reset_password_failure.html',
            scope: $scope
         });
      });
   };
}]);

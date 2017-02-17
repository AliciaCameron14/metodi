var app = angular.module('app', ['ngRoute', 'ui.bootstrap', 'route-segment',
  'view-segment', 'ngIdle'
]);

app.config(['KeepaliveProvider', 'IdleProvider', function(KeepaliveProvider, IdleProvider) {
  IdleProvider.idle(20*60);
  IdleProvider.timeout(60);
  // KeepaliveProvider.interval(10);
}]);

app.factory("services", ['$http', function($http) {
  var serviceBase = 'services/';
  var obj = {};

  // obj.setCurrentUser = function () {
  //   return $http.get(serviceBase + 'setCurrentUser');
  // };

obj.updateChain = function(chain) {
  return $http.post(serviceBase + 'updateChain', chain);
};

obj.getChain = function() {
  return $http.post(serviceBase + 'getChain');
};

  obj.getCurrentUser = function() {
    return $http.post(serviceBase + 'getCurrentUser');
  };

  obj.clearCurrentUser = function() {
    return $http.post(serviceBase + 'clearCurrentUser');
  };


  obj.login = function(user) {
    return $http.post(serviceBase + 'login', user).then(function(
      results) {
      return results;
    });
  };


  obj.getRequirements = function() {
      return $http.get(serviceBase + 'getRequirements');
    }

    obj.getFunctionalities = function(requirement) {
        return $http.post(serviceBase + 'getFunctionalities', requirement).then(function(results) {
          return results;
        });
      };

      obj.getExamples = function(functionality) {
          return $http.post(serviceBase + 'getExamples', functionality).then(function(results) {
            return results;
          });
        };
    // obj.getCustomer = function(customerID){
    //     return $http.get(serviceBase + 'customer?id=' + customerID);
    // }

  obj.addNewUser = function(user) {
    return $http.post(serviceBase + 'insertUser', user).then(function(
      results) {
      return results;
    });
  };

  // obj.updateCustomer = function (id,customer) {
  //     return $http.post(serviceBase + 'updateCustomer', {id:id, customer:customer}).then(function (status) {
  //         return status.data;
  //     });
  // };
  //
  // obj.deleteCustomer = function (id) {
  //     return $http.delete(serviceBase + 'deleteCustomer?id=' + id).then(function (status) {
  //         return status.data;
  //     });
  // };

  return obj;

}]);


app.service('steps', ['$rootScope','services', function($rootScope, services) {

  var allRequirements = {};
  var breadcrumbs = {};
var chain = {step: {}, requirement: {}, functionality: {}, example: {}};//contains the chosen step details eg: R1 -> F2 -> V3 and its details

// chain = services.getChain().then(function (data) {
//   console.log("service get chain ");
//   console.log(data);
//   if (data.data) {
//     return data.data;
//   }
//   else {
//     return '';
//   }
// })

function getChainData(){
  return services.getChain().then(function(data){

    if (!data.data) {

chain.step = 1;
      services.updateChain(chain);
      return chain;
    }
    else {
      console.log(data.data);
      return data.data;
    }
  });
}

function getRequirements(){
  return services.getRequirements().then(function(data){
    return data.data;
  });
}

return {getChainData:getChainData,
getRequirements:getRequirements};

// return {
//         chainData: function () {
//           console.log("FUNCTION");
//
//             return services.getChain();
//
//         }
//       }
// return {

  // chainData: function(){
  //   services.getChain().then(function(data){
  //     if (data.data) {
  //       chain = data.data;
  //       console.log("data");
  //
  //     } else {
  //       chain = {step: {}, requirement: {}, functionality: {}, example: {}};
  //       console.log("no data");
  //
  //     }
  //     // return chain;
  //   })
  //   console.log(chain);
  //
  //   return chain;
  //
  // }
// }
// function chainData() {
//   services.getChain().then(function(data){
//     if (data.data) {
//       chain = data.data;
//       return chain;
//     } else return {};
//   })
// }

}]);

// app.run(['Idle', function(Idle) {
//   Idle.watch();
// }]);

// app.run(['$location', '$rootScope', function($location, $rootScope) {
//     $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
//         $rootScope.title = current.$$route.title;
//     });
// }]);

// app.service('server', ['$rootScope', '$http', '$q', '$uibModal', function ($rootScope, $http, $q, $uibModal) {

//  $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

// this.call =  function (controller, action, data, noDefaultError, abortPromise, retryInterval) {

// $http.post('/metodi/index.php/user/do_add_user',data).then( function (response) {
//   console.log(response);
// });

//     var runCall, key, obj = this;
// if (!retryInterval) { retryInterval = 1000; }
// if (!data) { data = {}; }
// var promise = $q(function (resolve, reject) {
//    var createCall, config = (abortPromise && abortPromise.promise) ? { timeout: abortPromise.promise } : null;
//    createCall = function () {
//       $http.post(actionUrl(controller, action), data, config).then(
//
//          function (response) {   console.log("RESPONSE");
//            console.dir(response);resolve(response.data); },
//          function (response) {
//             if (response.status >= 300 && response.status < 600) {
//
//                var error = response.data;
//                if (!error || !error.error) { error = { error: 1, message: error || 'Server error.' }; }
//                error.status = response.status;
//                if (!noDefaultError) {
//                   var scope = $rootScope.$new();
//                   scope.controller = controller;
//                   scope.action = action;
//                   scope.data = data;
//                   scope.error = error;
//                   $modal.open({
//                      templateUrl: './views/error.html',
//                      scope: scope
//                   });
//                }
//                reject(error);
//                return;
//             }
//             setTimeout(createCall, retryInterval);
//             retryInterval *= 2;
//             if (retryInterval > 60000) { retryInterval = 60000; }
//          }
//       );
//    };
//    createCall();
// });
// return (promise);
// };

// }]

// );

// function actionUrl (controller, action)
// {
//   console.log("actionUrl function");
//    return ('/metodi/' + 'index.php/' + controller + '/' + action + '/');
// }

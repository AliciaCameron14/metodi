var app = angular.module('app', ['ngRoute', 'ui.bootstrap', 'route-segment',
  'view-segment', 'ngIdle', 'd3', 'ui.toggle', 'angularFileUpload',
  'ngSanitize'
]);

app.config(['KeepaliveProvider', 'IdleProvider', 'TitleProvider', function(
  KeepaliveProvider, IdleProvider, TitleProvider) {
  IdleProvider.idle(20 * 60);
  IdleProvider.timeout(60);
  TitleProvider.enabled(false);
  // KeepaliveProvider.interval(10);
}]);

app.factory("services", ['$http', function($http) {
  var serviceBase = 'services/';
  var obj = {};


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

  obj.forgotPassword = function(email) {
    return $http.post(serviceBase + 'forgotPassword', email).then(
      function(
        results) {
        return results;
      });
  };


  obj.getRequirements = function(id) {
    return $http.post(serviceBase + 'getRequirements', id).then(
      function(results) {
        return results;
      });
  }

  obj.getFunctionalities = function(requirement) {
    return $http.post(serviceBase + 'getFunctionalities', requirement).then(
      function(results) {
        return results;
      });
  };


  obj.getExamples = function(functionality) {
    return $http.post(serviceBase + 'getExamples', functionality).then(
      function(results) {
        return results;
      });
  };

  obj.getExample = function(ex) {
    return $http.post(serviceBase + 'getExample', ex).then(function(
      results) {
      return results;
    });
  };

  obj.getWordleFunctionalities = function(functionalities) {
    return $http.post(serviceBase + 'getWordleFunctionalities',
      functionalities).then(function(results) {
      return results;
    });
  };

  obj.getWordleExamples = function(functionality) {
    return $http.post(serviceBase + 'getWordleExamples', functionality)
      .then(function(results) {
        return results;
      });
  };

  obj.getWords = function() {
    return $http.get(serviceBase + 'getWords');
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

  obj.editRequirement = function(req) {
    return $http.post(serviceBase + 'editRequirement', req).then(
      function(
        results) {
        return results;
      });
  };

  obj.addRequirement = function(req) {
    return $http.post(serviceBase + 'addRequirement', req).then(
      function(
        results) {
        return results;
      });
  };

  obj.deleteRequirement = function(req) {
    return $http.post(serviceBase + 'deleteRequirement', req).then(
      function(
        results) {
        return results;
      });
  };

  obj.editFunctionality = function(func) {
    return $http.post(serviceBase + 'editFunctionality', func).then(
      function(
        results) {
        return results;
      });
  };

  obj.addFunctionality = function(func) {
    return $http.post(serviceBase + 'addFunctionality', func).then(
      function(
        results) {
        return results;
      });
  };

  obj.deleteFunctionality = function(func) {
    return $http.post(serviceBase + 'deleteFunctionality', func).then(
      function(
        results) {
        return results;
      });
  };

  obj.editFunctionalityLinks = function(func) {
    return $http.post(serviceBase + 'editFunctionalityLinks', func).then(
      function(
        results) {
        return results;
      });
  };

  obj.editExample = function(ex) {
    return $http.post(serviceBase + 'editExample', ex).then(function(
      results) {
      return results;
    });
  };

  obj.addExample = function(ex) {
    return $http.post(serviceBase + 'addExample', ex).then(function(
      results) {
      return results;
    });
  };

  obj.deleteExample = function(ex) {
    return $http.post(serviceBase + 'deleteExample', ex).then(function(
      results) {
      return results;
    });
  };

  obj.addExampleImg = function(ex) {
    return $http.post(serviceBase + 'addExampleImg', ex).then(function(
      results) {
      return results;
    });
  };

  obj.deleteExampleImg = function(ex) {
    return $http.post(serviceBase + 'deleteExampleImg', ex).then(
      function(
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


app.service('steps', ['$rootScope', 'services', function($rootScope, services) {

  var allRequirements = {};
  var breadcrumbs = {};
  var chain = {
    step: {},
    requirement: {},
    functionality: {},
    example: {}
  }; //contains the chosen step details eg: R1 -> F2 -> V3 and its details

  function getChainData() {
    return services.getChain().then(function(data) {

      if (!data.data) {
        chain.step = 1;
        services.updateChain(chain);
        return chain;
      } else {
        return data.data;
      }
    });
  }

  function getRequirements() {
    return services.getRequirements().then(function(data) {
      return data.data;
    });
  }

  return {
    getChainData: getChainData,
    getRequirements: getRequirements
  };


}]);

app.service('wordle', ['$rootScope', 'services', function($rootScope, services) {

  function getWords() {
    return services.getWords().then(function(data) {
      var words = data.data;
      var displayWords = [];
      for (var i = 0; i < words.length; i++) {
        displayWords.push(words[i].word);
      }
      return displayWords;
    });
  }

  return {
    getWords: getWords
  };

}]);

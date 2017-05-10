app.controller('searchController', ['$scope', '$location', 'services', function(
  $scope, $location, services) {

  services.getRequirements().then(
    function(data) {
      $scope.requirements = data.data;
    });

  services.getFunctionalities().then(
    function(data) {
      $scope.functionalities = data.data;
    });

  services.getExamples().then(
    function(data) {
      $scope.examples = data.data;
    });

  $scope.searchContent = function() {
    $scope.keywords = $scope.search.split(" ");
    $scope.requirementResults = $scope.getResults($scope.requirements,
      $scope.keywords);
    $scope.functionalityResults = $scope.getResults($scope.functionalities,
      $scope.keywords);
    $scope.exampleResults = $scope.getResults($scope.examples, $scope.keywords);
  }

  $scope.getResults = function(data, keywords) {
    var results = [];
    for (i = 0; i < data.length; i++) {
      for (var key in data[i]) {
        for (j = 0; j < keywords.length; j++) {
          if (data[i][key].indexOf(keywords[j]) != -1) {
            results.push(data[i]);
            break;
          }
        }
      }
    }

    var uniqueResults = [];
    $.each(results, function(i, el) {
      if ($.inArray(el, uniqueResults) === -1) uniqueResults.push(
        el);
    });
    return uniqueResults;
  }

  $scope.selectResult = function(step, item) {
    var chain = {
      step: {},
      requirement: {},
      functionality: {},
      example: {}
    };
    chain.step = step;
    switch (chain.step) {
      case 2:
        chain.requirement = item;
        services.updateChain(chain);
        $location.path('/requirements/functionalities');
        break;

      case 3:
        chain.functionality = item;

        for (var i = 0; i < $scope.requirements.length; i++) {
          if ($scope.requirements[i].requirementId == item.requirementId) {
            chain.requirement = $scope.requirements[i];
          }
        }

        services.updateChain(chain);
        $location.path('/requirements/functionalities/basis');
        break;

      case 4:
        chain.example = item;

        for (var i = 0; i < $scope.functionalities.length; i++) {
          if ($scope.functionalities[i].functionalityId == item.functionalityId) {
            chain.functionality = $scope.functionalities[i];

            for (var j = 0; j < $scope.requirements.length; j++) {
              if ($scope.requirements[j].requirementId == $scope.functionalities[
                  i].requirementId) {
                chain.requirement = $scope.requirements[j];
              }
            }
          }
        }

        services.updateChain(chain);
        $location.path('/requirements/functionalities/basis/example');
        break;
    }
    $scope.selectTab();
  }


}]);

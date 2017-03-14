app.controller('topicsController', ['$scope', '$location', 'services', 'words',
  '$route',
  function(
    $scope, $location, services, words, $route) {

    $scope.wordSelected = false;
    $scope.words = words;

    services.getRequirements().then(function(data) {
      $scope.requirements = data.data;
    });

    services.getFunctionalities().then(function(data) {
      $scope.functionalities = data.data;
    });

    services.getExamples().then(function(data) {
      $scope.examples = data.data;
    });

    services.getWords().then(function(data) {
      $scope.wordsData = data.data;
    });

    $scope.selectWord = function(word) {
      $scope.requirementLinks = [];
      $scope.functionalityLinks = [];
      $scope.exampleLinks = [];

      for (var i = 0; i < $scope.wordsData.length; i++) {
        if ($scope.wordsData[i].word == word.text) {

          //requirements
          for (var j = 0; j < $scope.wordsData[i].requirements.length; j++) {
            for (var k = 0; k < $scope.requirements.length; k++) {
              if ($scope.requirements[k].requirementId == $scope.wordsData[
                  i].requirements[j]) {
                $scope.requirementLinks.push($scope.requirements[k]);
              }
            }
          }

          //functionalities
          for (var m = 0; m < $scope.wordsData[i].functionalities.length; m++) {
            for (var n = 0; n < $scope.functionalities.length; n++) {
              if (($scope.functionalities[n].functionalityId == ($scope.wordsData[
                  i].functionalities[m]).substr(2, 2)) && $scope.functionalities[
                  n].requirementId == ($scope.wordsData[i].functionalities[
                  m]).substr(0, 2)) {
                $scope.functionalityLinks.push($scope.functionalities[n]);
              }
            }
          }

          //examples
          for (var a = 0; a < $scope.wordsData[i].examples.length; a++) {
            for (var s = 0; s < $scope.examples.length; s++) {
              if (($scope.examples[s].exampleId == ($scope.wordsData[i].examples[
                  a]).substr(4, 2)) && ($scope.examples[s].functionalityId ==
                  ($scope.wordsData[i].examples[a]).substr(2, 2)) && (
                  $scope.examples[s].requirementId == ($scope.wordsData[i]
                    .examples[a]).substr(0, 2))) {
                $scope.exampleLinks.push($scope.examples[s]);
              }
            }
          }
        }
      }
      $scope.wordSelected = true;
      $route.reload();
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
          for (var i = 0; i < $scope.requirementLinks.length; i++) {
            if ($scope.requirementLinks[i].requirementId == item.requirementId) {
              chain.requirement = $scope.requirementLinks[i];
            }
          }

          services.updateChain(chain);
          $location.path('/requirements/functionalities/basis');
          break;

        case 4:
          chain.example = item;

          for (var i = 0; i < $scope.functionalityLinks.length; i++) {
            if ($scope.functionalityLinks[i].functionalityId == item.functionalityId) {
              chain.functionality = $scope.functionalityLinks[i];

              for (var j = 0; j < $scope.requirementLinks.length; j++) {
                if ($scope.requirementLinks[j].requirementId == $scope.functionalityLinks[
                    i].requirementId) {
                  chain.requirement = $scope.requirementLinks[j];
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

  }
]);

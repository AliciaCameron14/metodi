app.controller('topicsController', ['$scope', '$location', 'services', 'words',
  function(
    $scope, $location, services, words) {

    $scope.words = words;
    $scope.wordSelected = false;

    services.getWords().then(
      function(data) {
        $scope.wordsData = data.data;

      });



    $scope.selectWord = function(word) {
      for (var i = 0; i < $scope.wordsData.length; i++) {
        if ($scope.wordsData[i].word == word.text) {
          services.getRequirements($scope.wordsData[i].requirements).then(
            function(data) {
              $scope.requirementLinks = data.data;
            });
          services.getWordleFunctionalities($scope.wordsData[i].functionalities)
            .then(function(data) {
              $scope.functionalityLinks = data.data;
            });
          services.getWordleExamples($scope.wordsData[i].examples).then(
            function(data) {
              $scope.exampleLinks = data.data;
            });
        }
      }
      $scope.wordSelected = true;
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
                if ($scope.requirementLinks[j].requirementId == $scope.functionalityLinks[i].requirementId ) {
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

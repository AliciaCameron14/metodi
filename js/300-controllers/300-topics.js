app.controller('topicsController', ['$scope', '$location', 'services', 'words',
  '$route', '$uibModal',
  function(
    $scope, $location, services, words, $route, $uibModal) {

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

      if ($scope.findLinks(word)) {
        $scope.wordSelected = true;
        $route.reload();
      }
    }

    $scope.findLinks = function(word = $scope.item.word) {

      var hasLinks = false;
      $scope.requirementLinks = [];
      $scope.functionalityLinks = [];
      $scope.exampleLinks = [];
      $scope.availableRequirements = [];
      $scope.availableFunctionalities = [];
      $scope.availableExamples = [];

      for (var i = 0; i < $scope.wordsData.length; i++) {
        if ($scope.wordsData[i].word == word) {

          //requirements
          for (var j = 0; j < $scope.wordsData[i].requirements.length; j++) {
            for (var k = 0; k < $scope.requirements.length; k++) {
              if ($scope.requirements[k].requirementId == $scope.wordsData[                  i].requirements[j]) {
                $scope.requirementLinks.push($scope.requirements[k]);
                hasLinks = true;
              } else {
                $scope.availableRequirements.push($scope.requirements[k]);
              }
            }
          }

          //functionalities
          for (var m = 0; m < $scope.wordsData[i].functionalities.length; m++) {
            for (var n = 0; n < $scope.functionalities.length; n++) {

              var funcId = $scope.wordsData[i].functionalities[m].substr(2);
              var index = $scope.wordsData[i].functionalities[m].indexOf('F');
              var reqId = $scope.wordsData[i].functionalities[m].substr(0, index);

              if (($scope.functionalities[n].functionalityId == funcId) &&
                ($scope.functionalities[n].requirementId == reqId)) {
                $scope.functionalityLinks.push($scope.functionalities[n]);
                hasLinks = true;
              } else {
                $scope.availableFunctionalities.push($scope.functionalities[n]);
              }
            }
          }

          //examples
          for (var a = 0; a < $scope.wordsData[i].examples.length; a++) {
            for (var s = 0; s < $scope.examples.length; s++) {

              var indexV = $scope.wordsData[i].examples[a].indexOf('V');
              var indexF = $scope.wordsData[i].examples[a].indexOf('F');

              var reqId = $scope.wordsData[i].examples[a].substr(0, indexF);
              var funcId = $scope.wordsData[i].examples[a].substring(indexF, indexV);
              var exId = $scope.wordsData[i].examples[a].substr(indexV);

              if (($scope.examples[s].exampleId == exId) && ($scope.examples[
                  s].functionalityId == funcId) && ($scope.examples[s].requirementId == reqId)) {
                $scope.exampleLinks.push($scope.examples[s]);
                hasLinks = true;
              } else {
                $scope.availableExamples.push($scope.examples[s]);
              }
            }
          }
        }
      }
      $scope.availableRequirements = $scope.requirements.filter(function(item) {
        return $scope.requirementLinks.indexOf(item) === -1;
      });

      $scope.availableFunctionalities = $scope.functionalities.filter(function(item) {
        return $scope.functionalityLinks.indexOf(item) === -1;
      });

      $scope.availableExamples = $scope.examples.filter(function(item) {
        return $scope.exampleLinks.indexOf(item) === -1;
      });

      return hasLinks;
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


    $scope.editWordle = function() {

      return ($uibModal.open({
        templateUrl: './views/admin/wordle_edit.html',
        scope: $scope
      }).result.then(function() {

      }))

    }

    //bring up modal to see what links the word has
    $scope.editWord = function(item) {
      $scope.item = angular.copy(item);

      return ($uibModal.open({
        templateUrl: './views/admin/word_edit.html',
        scope: $scope
      }).result.then(function() {

      }))
    }

    //add a new word to the wordle
    $scope.addWord = function() {
      $scope.item = {};
      return ($uibModal.open({
        templateUrl: './views/admin/wordle_add.html',
        scope: $scope
      }).result.then(function() {
        services.addWord($scope.item).then(function() {
          $scope.wordSelected = true;
          services.getWords().then(function(data) {
            $scope.wordsData = data.data;
            $scope.words.push($scope.item.word);
            $scope.wordSelected = false;
          });
        });
      }))
    }

    //remove a word from the wordle
    $scope.removeWord = function(item) {
      $scope.item = angular.copy(item);
      return ($uibModal.open({
        templateUrl: './views/admin/wordle_delete.html',
        scope: $scope
      }).result.then(function() {
        services.removeWord($scope.item).then(function() {
          $scope.wordSelected = true;
          services.getWords().then(function(data) {
            $scope.wordsData = data.data;
            var i = $scope.words.indexOf($scope.item.word);
            $scope.words.splice(i, 1);
            $scope.wordSelected = false;
          });
        });
      }))
    }

    //add a new link to a word in the wordle
    $scope.addLink = function(type) {
      $scope.item.selected = "";
      $scope.linkType = type;

      return ($uibModal.open({
        templateUrl: './views/admin/word_addLink.html',
        scope: $scope
      }).result.then(function() {
        var link = $scope.item.selected;

        switch (type) {
          case 1:
            $scope.item.requirements.push(link.requirementId);
            break;
          case 2:
            var linkName = link.requirementId + link.functionalityId;
            $scope.item.functionalities.push(linkName);
            break;
          case 3:
            var linkName = link.requirementId + link.functionalityId +
              link.exampleId;
            $scope.item.examples.push(linkName);
            break;
          default:
        }

        services.editWord($scope.item).then(function() {
          services.getWords().then(function(data) {
            $scope.wordsData = data.data;
            $scope.findLinks($scope.item.word);
          });

        });
      }))
    }

    //remove a link from a word
    $scope.removeLink = function(type, link) {

      switch (type) {
        case 1:
          var linkName = link.requirementId;
          for (var i = 0; i < $scope.item.requirements.length; i++) {
            if ($scope.item.requirements[i] == linkName) {
              $scope.item.requirements.splice(i, 1);
            }
          }

          break;
        case 2:
          var linkName = link.requirementId + link.functionalityId;
          for (var k = 0; k < $scope.item.functionalities.length; k++) {
            if ($scope.item.functionalities[k] == linkName) {
              $scope.item.functionalities.splice(k, 1);
            }
          }

          break;
        case 3:
          var linkName = link.requirementId + link.functionalityId +
            link.exampleId;
          for (var l = 0; l < $scope.item.examples.length; l++) {
            if ($scope.item.examples[l] == linkName) {
              $scope.item.examples.splice(l, 1);
            }
          }
          break;
        default:
      }

      services.editWord($scope.item).then(function() {

      });

      return ($uibModal.open({
        templateUrl: './views/admin/word_removeLink.html',
        scope: $scope
      }).result.then(function() {
        services.getWords().then(function(data) {
          $scope.wordsData = data.data;
          $scope.findLinks($scope.item.word);
        });

      }))
    }
  }
]);

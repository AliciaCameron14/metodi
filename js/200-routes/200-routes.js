app.config(['$routeSegmentProvider', '$routeProvider', '$locationProvider', function ($routeSegmentProvider, $routeProvider, $locationProvider)
    {
        $routeSegmentProvider
//        .when('/start','start')
            .when('/start/requirements', 'start.requirements')
            .when('/start/functionalities', 'start.functionalities')
            .when('/start/basis', 'start.basis')
            .when('/start/example', 'start.example')
            .when('/search', 'search')
            .when('/topics', 'topics')

        .segment('start', {
              templateUrl: './views/start/step1_requirements.php',
                controller: 'requirementsController'
        })
            .within()
            .segment('requirements', {
                templateUrl: './views/start/step1_requirements.php',
                controller: 'requirementsController'
            })
            .segment('functionalities', {
                templateUrl: './views/start/step2_functionalities.php',
                controller: 'functionalitiesController'
            })
            .segment('basis', {
                templateUrl: './views/start/step3_basis.php',
                controller: 'basisController'
            })
            .segment('example', {
                templateUrl: './views/start/step4_example.php',
                controller: 'exampleController'
            })
            .up() //start
            .segment('search', {
                templateUrl: './views/search/search.php',
                controller: 'searchController'
            })
            .segment('topics', {
                templateUrl: './views/topics/topics.php',
                controller: 'topicsController'
            });
        //        .otherwise({redirectTo: '/start'});
        $locationProvider.html5Mode(true);
}]);
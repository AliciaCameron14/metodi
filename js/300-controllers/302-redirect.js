app.controller('redirectController', ['$scope', '$routeSegment', '$location',
  'services',  function($scope, routeSegment, $location, services) {

    server.getCurrentUser().then(function (user) {
         if (!user) { window.location.href = baseUrl; }
         else
         {
            if (user.userType == 1) {
               $location.path($routeSegment.getSegmentUrl('globaladmin'));
            }
            // else if (user.userType == 3) {
            //    $location.path($routeSegment.getSegmentUrl('teacher'));
            // }
         }
      },
      function () { window.location.href = baseUrl; });

  }
]);

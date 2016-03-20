var app = angular.module('bankiaApp',['ui.router','ui.bootstrap','colorpicker.module']);

app.config(function($stateProvider,$urlRouterProvider) {

        $urlRouterProvider.otherwise('/menu/servicioNegocio');

        $stateProvider.state('menu', {
                url: '/menu',
                templateUrl : 'partials/menu.html',
                controller  : 'menuCtrl'
        });
        
        $stateProvider.state('servicioNegocio', {
                parent: 'menu',
                url: '/servicioNegocio',
                templateUrl : 'partials/servicioNegocio/servicioNegocio.html',
                controller  : 'servicioNegocioCtrl'
        });
        $stateProvider.state('subProceso', {
                parent: 'menu',
                url: '/subProceso',
                templateUrl : 'partials/subProceso/subProceso.html',
                controller: 'subProcesoCtrl'
        });
        $stateProvider.state('front', {
                parent: 'menu',
                url: '/front',
                templateUrl : 'partials/front/front.html',
                controller: 'frontCtrl'
        });
});



app.run(function ($rootScope, $state) {
    $rootScope.$on("$stateChangeStart", function(event, toState, toParams, fromState, fromParams){
      /*  var connected = loginService.islogged();
        connected.then(function(msg){
            if (!msg.data){
                console.log("redireccionando login");
                $state.transitionTo("login");
                event.preventDefault();
            }
        });*/
    });
});



app.controller('NavigationCtrl', ['$scope', '$location', function ($scope, $location) {
    $scope.isCurrentPath = function (path) {
      return $location.path() == path;
    };
}]);


app.directive('bootstrapTooltip', function() {
  return function(scope, element, attrs) {
    attrs.$observe('title',function(title){
      // Destroy any existing tooltips (otherwise new ones won't get initialized)
      element.tooltip('destroy');
      // Only initialize the tooltip if there's text (prevents empty tooltips)
      if (jQuery.trim(title)) element.tooltip();
    })
    element.on('$destroy', function() {
      element.tooltip('destroy');
      delete attrs.$$observers['title'];
    });
  }
});
angular.module('rqportal', [
    'config',
    'ng-bs3-datepicker',
    'angucomplete-alt',
    'chart.js',
    'ui.bootstrap',
    'ui.utils.masks',
    'uiGmapgoogle-maps',
    'controllers.dashboard',
    'controllers.applicant',
    'controllers.quote',
    'controllers.billing'
])
.config(function($interpolateProvider) {
    // Avoid confliction with Phalcon
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
})
.config(function(uiGmapGoogleMapApiProvider){
    uiGmapGoogleMapApiProvider.configure({
        key: 'AIzaSyCJLXHwzgv6qUZ8qtQhYvKm03173zJ2kyQ',
        v: '3.17',
        libraries: 'places,weather,geometry,visualization,drawing'
    });
})
.controller('AppCtrl', function($rootScope){
    $rootScope.loading = 0;
})


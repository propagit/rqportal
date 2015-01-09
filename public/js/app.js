angular.module('rqportal', [
    'config',
    'ui.bootstrap',
    'ui.utils.masks',
    'uiGmapgoogle-maps'
])
.config(function($interpolateProvider) {
    // Avoid confliction with Phalcon
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
})
.config(function(uiGmapGoogleMapApiProvider){
    uiGmapGoogleMapApiProvider.configure({
        key: 'AIzaSyBXS2w40hmb0AKyCIRTj8AaVHSFQ4cnYEQ',
        v: '3.17',
        libraries: 'places,weather,geometry,visualization'
    });
})
.controller('LocalMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -37.8602828, longitude: 145.079616 }, zoom: 9 };
        $scope.options = {scrollwheel: false};

        $http.post(Config.BASE_URL + 'applicant/allLocal')
        .success(function(response){
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
            });
            response.circles.forEach(function(circle){
                $scope.circles.push(circle);
            });
            response.markers.forEach(function(marker){
                $scope.markers.push(marker);
            });
        })
        .error(function(error){
            console.log("Error gettting zones: ", error);
        });
    });
    $scope.addZone = function(postcode, distance) {
        var geocoder = new google.maps.Geocoder();
        var address = postcode + " Australia";
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                $http.post(Config.BASE_URL + 'applicant/addLocal', {
                    postcode: postcode,
                    latitude: results[0].geometry.location.lat(),
                    longitude: results[0].geometry.location.lng(),
                    distance: distance
                }).success(function(response){
                    $scope.zones.push(response.zone);
                    $scope.circles.push(response.circle);
                    $scope.markers.push(response.marker);
                }).error(function(error){
                    console.log("Error adding zone: ", error);
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $http.post(Config.BASE_URL + 'applicant/deleteLocal/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles.splice(i, 1);
                    $scope.markers.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("Error deleting zone: ", error);
        });
    };
})

.controller('CountryMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles = [];
    $scope.markers = [];
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -37.8602828, longitude: 145.079616 }, zoom: 7 };
        $http.post(Config.BASE_URL + 'applicant/allCountry')
        .success(function(response){
            // console.log(response);
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
            });
            response.circles.forEach(function(circle){
                $scope.circles.push(circle);
            });
            response.markers.forEach(function(marker){
                $scope.markers.push(marker);
            });
        })
        .error(function(error){
            console.log("Error getting zones: ", error);
        });
    });
    $scope.addZone = function(local_id, distance) {
        $http.post(Config.BASE_URL + 'applicant/addCountry', {
            local_id: local_id,
            distance: distance
        }).success(function(response) {
            // console.log(response);
            $scope.zones.push(response.zone);
            $scope.circles.push(response.circle);
            $scope.markers.push(response.marker);
        }).error(function(error){
            console.log("Error adding zone: ", error);
        });
    };
    $scope.deleteZone = function(id) {
        $http.post(Config.BASE_URL + 'applicant/deleteCountry/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles.splice(i, 1);
                    $scope.markers.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("Error deleting zone: ", error);
        });
    };
})

.controller('StateMapCtrl', function($scope, $http, Config, uiGmapGoogleMapApi){
    $scope.zones = [];
    $scope.circles1 = [];
    $scope.circles2 = [];
    $scope.paths = [];
    $scope.polylines = [];
    uiGmapGoogleMapApi.then(function(maps) {
        $scope.map = { center: { latitude: -26.4390917, longitude: 133.281323 }, zoom: 4 };
        $http.post(Config.BASE_URL + 'applicant/allInterstate')
        .success(function(response){
            response.zones.forEach(function(zone){
                $scope.zones.push(zone);
            });
            response.circles1.forEach(function(circle){
                $scope.circles1.push(circle);
            });
            response.circles2.forEach(function(circle){
                $scope.circles2.push(circle);
            });
            response.paths.forEach(function(path){
                $scope.paths.push(path);
            });
            console.log($scope.paths);
        })
        .error(function(error){
            console.log("Error gettting zones: ", error);
        });
    });
    $scope.addZone = function(zone) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode( { 'address': zone.postcode1 + " Australia"}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK)
            {
                zone.latitude1 =  results[0].geometry.location.lat();
                zone.longitude1 = results[0].geometry.location.lng();
                geocoder.geocode( { 'address': zone.postcode2 + " Australia"}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK)
                    {
                        zone.latitude2 =  results[0].geometry.location.lat();
                        zone.longitude2 = results[0].geometry.location.lng();
                        $http.post(Config.BASE_URL + 'applicant/addInterstate', zone)
                        .success(function(response){
                            $scope.zones.push(response.zone);
                            $scope.circles1.push(response.circle1);
                            $scope.circles2.push(response.circle2);
                            $scope.paths.push(response.path);
                        }).error(function(error){
                            console.log("Error adding zone: ", error);
                        });
                    }
                });
            }
        });
    };

    $scope.deleteZone = function(id) {
        $http.post(Config.BASE_URL + 'applicant/deleteInterstate/' + id)
        .success(function(response){
            for(var i=0; i<$scope.zones.length; i++) {
                if ($scope.zones[i].id == id) {
                    $scope.circles1.splice(i, 1);
                    $scope.circles2.splice(i, 1);
                    $scope.paths.splice(i, 1);
                    $scope.zones.splice(i, 1);
                    break;
                }
            }
        }).error(function(error){
            console.log("Error deleting zone: ", error);
        });
    };
})

.controller('ApplicantPaymentCtrl', function($scope, $locale, $http, $modal, $timeout, Config) {
    $scope.currentYear = new Date().getFullYear();
    $scope.currentMonth = new Date().getMonth() + 1;
    $scope.months = $locale.DATETIME_FORMATS.MONTH;
    $scope.card = { // Populate for testing
        name: 'John Doe',
        number: '4444333322221111',
        month: 12,
        year: 16,
        cvv: 123,
        agree: false
    };
    $scope.complete = function() {
        var loadingModal = $modal.open({
            templateUrl: 'loading',
            size: '',
            backdrop: 'static',
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });

        $http.post(Config.BASE_URL + 'applicant/complete')
        .success(function(response){
            loadingModal.close();
            window.location = '..';
        }).error(function(error){

        });
    };
})
.filter('range', function(){
    var filter = function(arr, lower, upper) {
        for (var i = lower; i <= upper; i++) {
            arr.push(i);
        }
        return arr;

    }
    return filter;
})

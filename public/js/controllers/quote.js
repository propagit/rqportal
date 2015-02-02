angular.module('controllers.quote', [])

.controller('QuoteCtrl', function($rootScope, $scope, $http, $timeout, $modal, Config, uiGmapGoogleMapApi) {
    $scope.current_quote = {};
    $scope.removals = [];
    $scope.storages = [];

    if ($scope.query != 'un-allocated')
    {
        $scope.params = {
            from_date: moment().format("YYYY-MM-DD"),
        };
    }
    else
    {
        $scope.params = {
            allocated: 'not_allocated'
        };
    }

    angular.element(document).ready(function () {
        $scope.searchQuotes($scope.params);
    });


    $scope.searchQuotes = function(params) {
        $scope.removals = [];
        $scope.storages = [];
        $http.post(Config.BASE_URL + 'quoteAjax/search', params)
        .success(function(response){
            if(response.results && response.results.length > 0) {
                response.results.forEach(function(quote){
                    if (quote.removal) {
                        $scope.removals.push(quote);
                    } else {
                        $scope.storages.push(quote);
                    }
                });
                if ($scope.removals.length > 0) {
                    //$scope.removalDetails($scope.removals[0]);
                }
            } else {
                $scope.current_quote = {};
            }
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };
    $scope.removalDetails = function(quote) {
        var removal = quote.removal;
        $scope.current_quote = quote;
        $scope.paths = [];
        $scope.updateQuoteStatus(quote.id, 1);

        uiGmapGoogleMapApi.then(function(maps) {
            // Calculate Zoom
            var latlngList = [];
            latlngList.push(new google.maps.LatLng(removal.from_lat, removal.from_lon));
            latlngList.push(new google.maps.LatLng(removal.to_lat, removal.to_lon));
            var bounds = new google.maps.LatLngBounds();
            latlngList.forEach(function(n){
                bounds.extend(n);
            });
            var mapDim = {
                height: $('#map-wrapper').height() > 0 ? $('#map-wrapper').height() : 600,
                width: $('#map-wrapper').width() > 0 ? $('#map-wrapper').width() : 500
            };
            var zoom = getBoundsZoomLevel(bounds, mapDim);
            var lat = (parseFloat(removal.from_lat) + parseFloat(removal.to_lat))/2;
            var lon = (parseFloat(removal.from_lon) + parseFloat(removal.to_lon))/2;

            $scope.map = { center: { latitude: lat, longitude: lon }, zoom: zoom };
            $scope.paths.push(removal.path);
            $scope.from_marker = removal.from_marker;
            $scope.to_marker = removal.to_marker;
        });
    };
    $scope.storageDetails = function(quote) {
        var storage = quote.storage;
        $scope.current_quote = quote;
        $scope.updateQuoteStatus(quote.id, 1);

        uiGmapGoogleMapApi.then(function(maps) {
            $scope.map = { center: { latitude: storage.pickup_lat, longitude: storage.pickup_lon }, zoom: 10 };
            $scope.paths = [];
            $scope.from_marker = storage.pickup_marker;
            $scope.to_marker = {};
        });
    };

    $scope.updateQuoteStatus = function(id, status) {
        $http.post(Config.BASE_URL + 'quote/ajaxUpdate/' + id , {status: status })
        .success(function(quote){
            if (quote.job_type == 'removal') {
                var updated_removals = [];
                $scope.removals.forEach(function(removal_quote){
                    if (removal_quote.id == quote.id) {
                        updated_removals.push(quote);
                    } else {
                        updated_removals.push(removal_quote);
                    }
                });
                $scope.removals = updated_removals;
            } else { // Storage
                var updated_storages = [];
                $scope.storages.forEach(function(storage_quote) {
                    if (storage_quote.id == quote.id) {
                        updated_storages.push(quote);
                    } else {
                        updated_storages.push(storage_quote);
                    }
                });
                $scope.storages = updated_storages;
            }
            $scope.current_quote = quote;
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };



    $scope.deleteQuote = function() {
        var quote = $scope.current_quote;
        $http.delete(Config.BASE_URL + 'quoteajax/deleteQuote/' + quote.id)
        .success(function(response){
            console.log(response);
            if (quote.job_type == 'removal') {
                var updated_removals = [];
                $scope.removals.forEach(function(removal_quote){
                    if (removal_quote.id != quote.id) {
                        updated_removals.push(removal_quote);
                    }
                });
                $scope.removals = updated_removals;
            } else { // Storage
                var updated_storages = [];
                $scope.storages.forEach(function(storage_quote) {
                    if (storage_quote.id != quote.id) {
                        updated_storages.push(storage_quote);
                    }
                });
                $scope.storages = updated_storages;
            }
            $scope.current_quote = {};
        }).error(function(error){
            console.log("ERROR: ", error);
        });
    };

    $scope.addSupplier = function(supplier, free, all_suppliers) {
        // console.log(supplier, free, all_suppliers); return;
        if (supplier && all_suppliers != 'YES') {
            $http.post(Config.BASE_URL + 'quoteajax/addSupplier', {
                quote_id: $scope.current_quote.id,
                supplier_id: supplier.originalObject.id,
                free: free
            })
            .success(function(response){
                $scope.current_quote.suppliers.push(response.supplier);
            }).error(function(error){
                $scope.error = error.message;
                $timeout(function(){
                    $scope.error = null;
                }, 4000);

                console.log("ERROR: ", error);
            });
        } else if (all_suppliers == 'YES') {
            $http.post(Config.BASE_URL + 'quoteajax/addAllSuppliers', {
                quote_id: $scope.current_quote.id,
                free: free
            }).success(function(response){
                response.suppliers.forEach(function(supplier){
                    $scope.current_quote.suppliers.push(supplier);
                });
            }).error(function(error){
                console.log("ERROR: ", error);
            });
        }
    };


    // Private function, calculate zoom level
    function getBoundsZoomLevel(bounds, mapDim) {
        var WORLD_DIM = { height: 256, width: 256 };
        var ZOOM_MAX = 21;

        function latRad(lat) {
            var sin = Math.sin(lat * Math.PI / 180);
            var radX2 = Math.log((1 + sin) / (1 - sin)) / 2;
            return Math.max(Math.min(radX2, Math.PI), -Math.PI) / 2;
        }

        function zoom(mapPx, worldPx, fraction) {
            return Math.floor(Math.log(mapPx / worldPx / fraction) / Math.LN2);
        }

        var ne = bounds.getNorthEast();
        var sw = bounds.getSouthWest();

        var latFraction = (latRad(ne.lat()) - latRad(sw.lat())) / Math.PI;

        var lngDiff = ne.lng() - sw.lng();
        var lngFraction = ((lngDiff < 0) ? (lngDiff + 360) : lngDiff) / 360;

        var latZoom = zoom(mapDim.height, WORLD_DIM.height, latFraction);
        var lngZoom = zoom(mapDim.width, WORLD_DIM.width, lngFraction);

        return Math.min(latZoom, lngZoom, ZOOM_MAX);
    };

})

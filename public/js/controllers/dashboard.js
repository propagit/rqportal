angular.module('controllers.dashboard', [])

.controller('DashboardCtrl', function($rootScope, $scope, $http, Config, $modal, uiGmapGoogleMapApi){

    $rootScope.loading++;
    $http.get(Config.BASE_URL + 'dashboardajax/getSales')
    .success(function(response){
        $scope.labels = response.labels;
        $scope.series = response.series;
        $scope.data = response.data;
        // console.log(response);
    }).error(function(error){
        console.log("ERROR: ", error);
    }).finally(function(){
        $rootScope.loading--;
    });

    $scope.options = {
        bezierCurve: false,
        scaleBeginAtZero: false,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%>$<%= value %>",
        multiTooltipTemplate: "$<%= value %>",
    };

    $scope.$watch('time', function(val) {
        // console.log(val);
        loadStats(val);
    });

    $scope.time = 'month';

    function loadStats(time) {
        $rootScope.loading++;
        $http.post(Config.BASE_URL + 'dashboardajax/getStats', {
            time: time
        }).success(function(response){
            $scope.stats = response;
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
            $rootScope.loading--;
        });
    };
	$scope.duplicateRemovals = [];
	$scope.duplicateRemovalsCount = 0;
	
    $scope.duplicateStorages = [];
	
	$scope.getDuplicateQuotes = function(){

        $http.get(Config.BASE_URL + 'quoteAjax/getDuplicateRemovalQuotes')
        .success(function(response){
            if(response.results && response.results.length > 0) {
				$scope.duplicateRemovals = response.results[0].removal;
				$scope.duplicateRemovalsCount = response.results[0].removal_duplicate_count;
             
            } 
        }).error(function(error){
            console.log("ERROR: ", error);
        }).finally(function(){
         
        });
	};
	
	$scope.confirmDelete = function(duplicateRemoval){
		//console.log(duplicateRemoval);
		$scope.modalInstance = $modal.open({
		  templateUrl: 'confirmDeleteDuplicate.html',
		 // controller: 'DashboardCtrl',
		  scope: $scope,
		  size: 'sm'
		});
		$scope.duplicateToDelete = duplicateRemoval;
  	};
	
	$scope.deleteDuplicate = function () {
		  $scope.deleteDuplicateRemoval($scope.duplicateToDelete);
		  $scope.modalInstance.dismiss()
	};
  
	$scope.cancel = function () {
		 $scope.modalInstance.dismiss()
	};
	
	$scope.deleteDuplicateRemoval = function(duplicateRemoval){
		  //console.log(duplicateRemoval.id);
		  $http.post(Config.BASE_URL + 'quoteAjax/deleteDuplicateRemovalQuote/' + duplicateRemoval.id)
		  .success(function(response){
			  //console.log(response);
			  $scope.getDuplicateQuotes();
		  }).error(function(error){
			  console.log(error);	
		  });
	};
	
	$scope.current_quote = {};

	$scope.removalDetails = function(quote,parent) {
		 var removal = parent;
		$scope.current_quote = quote;
        $scope.paths = [];
	
        $rootScope.loading++;
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
            $scope.to_marker =removal.to_marker;
            $rootScope.loading--;
        });
		//console.log($scope.current_quote);
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
	
	$scope.reSend = function(duplicateRemoval){
		//console.log(duplicateRemoval);
		$http.post(Config.BASE_URL + 'quoteAjax/reSendDuplicateRemovalQuote/' + duplicateRemoval.id)
		.success(function(response){
			//console.log(response);
			$scope.getDuplicateQuotes();
		}).error(function(error){
			console.log(error);	
		});
	};
	
	// needs new function on quoteajaxcontroller
	 $scope.addSupplier = function(supplier, free) {
        //console.log(supplier, free, all_suppliers);
		//console.log($scope.current_quote);
		//return;
        if (supplier) {
            $rootScope.loading++;
            $http.post(Config.BASE_URL + 'quoteajax/addSupplierToDuplicate', {
                quote_id: $scope.current_quote.id,
                supplier_id: supplier.originalObject.id,
                free: free
            })
            .success(function(response){
                $scope.current_quote.suppliers.push(response.supplier);
				//$scope.getDuplicateQuotes();
            }).error(function(error){
                $scope.error = error.message;
                $timeout(function(){
                    $scope.error = null;
                }, 4000);

                console.log("ERROR: ", error);
            }).finally(function(){
                $rootScope.loading--;
            });
        } 
    };
})

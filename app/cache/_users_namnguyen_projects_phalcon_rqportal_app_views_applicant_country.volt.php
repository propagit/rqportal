


<div class="row" ng-controller="CountryMapCtrl">
    <div class="col-lg-7">
        <h3>Create Your Country Zone</h3>
        <p>You will receive work in this zone provided a post code from your local zone is either in the pick up or drop off address.</p>

        <div class="map">
            <ui-gmap-google-map center='map.center' zoom='map.zoom'>
                <ui-gmap-circle ng-repeat="c in circles track by c.id" center="c.center" stroke="c.stroke" fill="c.fill" radius="c.radius"
                visible="c.visible" geodesic="c.geodesic" editable="c.editable" draggable="c.draggable" clickable="c.clickable"></ui-gmap-circle>

                <ui-gmap-circle ng-repeat="c in markers track by c.id" center="c.center" stroke="c.stroke" fill="c.fill" radius="c.radius"
                visible="c.visible" geodesic="c.geodesic" editable="c.editable" draggable="c.draggable" clickable="c.clickable"></ui-gmap-circle>
            </ui-gmap-google-map>
        </div>

    </div>
    <div class="col-lg-5">
        <h4>Add Your Country Zones</h4>
        <p>Choose a local zone then choose the distance from the local zone you can work in.</p>
        <form class="form-block" name="countryForm" novalidate>
            <div>
                <label for="local_id">Choose your local zone post code</label>
                <select ng-model="local_id" id="local_id" class="form-control" required>
                    <option disabled selected value="">Local Zone</option>
                    <?php foreach ($local_zones as $local_zone) { ?>
                    <option value="<?php echo $local_zone->id; ?>"><?php echo $local_zone->postcode; ?> - <?php echo $local_zone->distance; ?> km</option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="distance">Select a distance you can work from this post code</label>
                <input type="number" class="form-control" ng-model="distance" id="distance" required>
            </div>
            <div>
                <button class="btn btn-red" ng-disabled="countryForm.$invalid" ng-click="addZone(local_id, distance)">Add Zone</button>
            </div>
        </form>
        <hr />

        <h4>YOUR COUNTRY ZONES</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Local Zone</td>
                    <td>Distance</td>
                    <td width="80"></td>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="zone in zones">
                    <td>[[ zone.postcode + ' (' + zone.local + ' km)' ]]</td>
                    <td>[[ zone.distance ]] km</td>
                    <td align="center"><a class="btn btn-xs btn-danger" ng-click="deleteZone(zone.id)"><i class="fa fa-times"></i> Delete</a></td>
                </tr>
            </tbody>
        </table>
        <?php if ($goNext) { ?>
        <a href="<?php echo $baseUrl; ?>/location/interstate#map" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>Next Step
        </a>
        <?php } ?>
    </div>
</div>


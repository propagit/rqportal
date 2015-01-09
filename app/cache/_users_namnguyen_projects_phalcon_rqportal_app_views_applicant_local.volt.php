<?php $this->partial('applicant/header', array('step' => 2)); ?>


<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>What Locations Can you Work In</h3>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-red">
                <li<?php if ($zone == 'local') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/local#map">Local Zones</a></li>
                <li<?php if ($zone == 'country') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/country#map">Country Zones</a></li>
                <li<?php if ($zone == 'interstate') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/interstate#map">Interstate Zones</a></li>
            </ul>

            <!-- Tab panes -->
            <div id="map">
                
<div class="row" ng-controller="LocalMapCtrl">
    <div class="col-lg-7">
        <h3>Create Your Local Zone</h3>
        <p>Create a zone the covers your local area. You will receive jobs in this zone if the pick up or drop off postcodes fall within your zone.</p>

        <div class="map">
            <ui-gmap-google-map center='map.center' zoom='map.zoom'>
                <ui-gmap-circle ng-repeat="c in circles track by c.id" center="c.center" stroke="c.stroke" fill="c.fill" radius="c.radius"
                visible="c.visible" geodesic="c.geodesic" editable="c.editable" draggable="c.draggable" clickable="c.clickable"></ui-gmap-circle>

                <ui-gmap-marker ng-repeat="marker in markers track by marker.id" coords="marker.coords" options="marker.options" events="marker.events" idkey="marker.id">
                </ui-gmap-marker>
            </ui-gmap-google-map>
        </div>

    </div>
    <div class="col-lg-5">
        <h4>Add Your Local Zones</h4>
        <p>Add the post code of your local zone then choose the distance from the post code you can work in.</p>
        <form class="form-block" name="localForm" novalidate>
            <div>
                <label for="postcode">Enter your local zone post code</label>
                <input type="number" class="form-control" ng-model="postcode" id="postcode" required>
            </div>
            <div>
                <label for="distance">Select a distance you can work from this post code</label>
                <input type="number" class="form-control" ng-model="distance" id="distance" required>
            </div>
            <div>
                <button class="btn btn-danger" ng-disabled="localForm.$invalid" ng-click="addZone(postcode, distance)">Add Zone</button>
            </div>
        </form>
        <hr />

        <h4>YOUR LOCAL ZONES</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Postcode</td>
                    <td>Distance</td>
                    <td width="80"></td>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="zone in zones">
                    <td>[[ zone.postcode ]]</td>
                    <td>[[ zone.distance ]] km</td>
                    <td align="center"><a class="btn btn-xs btn-danger" ng-click="deleteZone(zone.id)"><i class="fa fa-times"></i> Delete</a></td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-red" ng-if="zones.length > 0" href="<?php echo $baseUrl; ?>/country#map">Next Step</a>
    </div>
</div>

            </div>
        </div>
    </div>
</div>

<br />

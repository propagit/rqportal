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
                
<div class="row" ng-controller="StateMapCtrl">
    <div class="col-lg-7">
        <h3>Create Your Interstate Zones</h3>
        <p>To set up your interstate zones create two locations such as Melbourne and Sydney and choose the distance from these two locations you are prepared to work in.</p>

        <div class="map">
            <ui-gmap-google-map center='map.center' zoom='map.zoom'>
                <ui-gmap-circle ng-repeat="c in circles1 track by c.id" center="c.center" stroke="c.stroke" fill="c.fill" radius="c.radius"
                visible="c.visible" geodesic="c.geodesic" editable="c.editable" draggable="c.draggable" clickable="c.clickable"></ui-gmap-circle>
                <ui-gmap-circle ng-repeat="c in circles2 track by c.id" center="c.center" stroke="c.stroke" fill="c.fill" radius="c.radius"
                visible="c.visible" geodesic="c.geodesic" editable="c.editable" draggable="c.draggable" clickable="c.clickable"></ui-gmap-circle>

                <ui-gmap-polyline ng-repeat="p in paths" path="p.path" stroke="p.stroke" visible='p.visible'
                  geodesic='p.geodesic' fit="false" editable="p.editable" draggable="p.draggable" icons='p.icons'></ui-gmap-polyline>
            </ui-gmap-google-map>
        </div>

    </div>
    <div class="col-lg-5">
        <h4>Add Your Interstate Zones</h4>
        <p>Create two interstate zones by entering a postcode and the distance from the post code.</p>
        <form class="form-block" name="interstateForm" novalidate>
            <div>
                <b>Interstate Zone 1</b>
                <label for="postcode">Enter a postcode for your interstate zone</label>
                <input type="number" class="form-control" ng-model="zone.postcode1" id="postcode" required>
            </div>
            <div>
                <label for="distance">Select a distance you can work from this post code</label>
                <input type="number" class="form-control" ng-model="zone.distance1" id="distance" required>
            </div>
            <div>
                <b>Interstate Zone 2</b>
                <label for="postcode2">Enter a postcode for your interstate zone</label>
                <input type="number" class="form-control" ng-model="zone.postcode2" id="postcode2" required>
            </div>
            <div>
                <label for="distance2">Select a distance you can work from this postcode</label>
                <input type="number" class="form-control" ng-model="zone.distance2" id="distance2" required>
            </div>
            <div>
                <button class="btn btn-red" ng-disabled="interstateForm.$invalid" ng-click="addZone(zone)">Add Zone</button>
            </div>
        </form>
        <hr />

        <h4>YOUR INTERSTATE ZONES</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Zone 1</td>
                    <td>Zone 2</td>
                    <td width="80"></td>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="zone in zones">
                    <td>[[ zone.postcode1 + ' (' + zone.distance1 + ' km)' ]]</td>
                    <td>[[ zone.postcode2 + ' (' + zone.distance2 + ' km)' ]]</td>
                    <td align="center"><a class="btn btn-xs btn-danger" ng-click="deleteZone(zone.id)"><i class="fa fa-times"></i> Delete</a></td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-red" href="<?php echo $baseUrl; ?>/payment">Next Step</a>
    </div>
</div>

            </div>
        </div>
    </div>
</div>

<br />

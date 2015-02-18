{#% extends "applicant/location.volt" %#}

{% block location %}
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
                <label for="postcode">Enter your local zone center</label>
                <angucomplete-alt id="postcode"
                              minlength="1"
                              placeholder="Enter postcode or suburb name"
                              pause="100"
                              selected-object="center"
                              remote-url="{{ baseUrl }}api/postcode/"
                              remote-url-data-field="postcodes"
                              title-field="name"
                              input-class="form-control"
                              match-class="highlight"
                              field-required="true"></angucomplete-alt>
            </div>
            <div>
                <label for="distance">Select a distance you can work from this post code</label>
                <input type="number" class="form-control" ng-model="distance" id="distance" required>
            </div>
            <div>
                <button class="btn btn-red" ng-disabled="localForm.$invalid" ng-click="addZone(center, distance)">Add Zone</button>
            </div>
        </form>

        <div ng-if="zones.length == 0" class="alert alert-warning">
            There are no local zones set up yet.
        </div>

        <div ng-if="zones.length > 0">
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
            {% endif %}
        </div>

        {% if goNext %}
        <a href="{{ baseUrl }}applicant/location/country#map" ng-if="zones.length > 0" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>Next Step
        </a>
        &nbsp;
        <a href="{{ baseUrl }}applicant/location/country#map" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>Skip
        </a>
    </div>
</div>
{% endblock %}

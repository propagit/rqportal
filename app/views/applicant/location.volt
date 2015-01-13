{% include "applicant/header" with ['step': 2] %}


<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>What Locations Can you Work In</h3>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-red">
                <li{% if zoneType == 'local' %} class="active"{% endif %}><a href="{{ baseUrl }}/location/local#map">Local Zones</a></li>
                <li{% if zoneType == 'country' %} class="active"{% endif %}><a href="{{ baseUrl }}/location/country#map">Country Zones</a></li>
                <li{% if zoneType == 'interstate' %} class="active"{% endif %}><a href="{{ baseUrl }}/location/interstate#map">Interstate Zones</a></li>
            </ul>

            <!-- Tab panes -->
            <div id="map">
                {% include "applicant/" ~ zoneType %}
            </div>
        </div>
    </div>
</div>

<br />

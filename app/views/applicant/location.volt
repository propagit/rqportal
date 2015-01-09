{% include "applicant/header" with ['step': 2] %}


<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>What Locations Can you Work In</h3>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-red">
                <li{% if zone == 'local' %} class="active"{% endif %}><a href="{{ baseUrl }}/local#map">Local Zones</a></li>
                <li{% if zone == 'country' %} class="active"{% endif %}><a href="{{ baseUrl }}/country#map">Country Zones</a></li>
                <li{% if zone == 'interstate' %} class="active"{% endif %}><a href="{{ baseUrl }}/interstate#map">Interstate Zones</a></li>
            </ul>

            <!-- Tab panes -->
            <div id="map">
                {% block location %}{% endblock %}
            </div>
        </div>
    </div>
</div>

<br />

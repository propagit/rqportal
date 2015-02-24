
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-map-marker"></i> Work Locations</h1>

        <a href="{{ baseUrl }}profile/company" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-user"></i></span>My Profile
        </a> &nbsp;

        <a href="#" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="fa fa-map-marker"></i></span>Work Locations
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/filter" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-comment-o"></i></span>Quote Filter
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/payment" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-credit-card"></i></span>Payment Info
        </a>
        <br /><br />
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <h3>What Locations Can You Work In?</h3>

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-11" data-widget-colorbutton="false" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false">

            <header>
                <ul id="widget-tab-1" class="nav nav-tabs pull-left">

                    <li{% if zoneType == 'local' %} class="active"{% endif %}>
                        <a href="{{ baseUrl }}profile/location/local">Local Zones</a>

                    </li>
                    <li{% if zoneType == 'country' %} class="active"{% endif %}>
                        <a href="{{ baseUrl }}profile/location/country">Country Zones</a>
                    </li>
                    <li{% if zoneType == 'interstate' %} class="active"{% endif %}>
                        <a href="{{ baseUrl }}profile/location/interstate">Interstate Zones</a>
                    </li>
                </ul>

            </header>

            <!-- widget div-->
            <div>

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <!-- widget body text-->

                    <div class="tab-content padding-10">
                        <div class="tab-pane fade in active"><br />
                            {% include "applicant/" ~ zoneType %}
                        </div>
                    </div>

                    <!-- end widget body text-->

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </div>
</div>

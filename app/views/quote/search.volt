
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-text-o"></i> Search Quotes</h1>
    </div>
</div>

<div class="row" ng-controller="QuoteCtrl">
    <div class="col-lg-5">
        <div class="jarviswidget" id="wid-1">

            <header>
                <ul id="widget-tab-1" class="nav nav-tabs pull-left">
                    <li class="active">
                        <a data-toggle="tab" href="#removal"> Removal </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#storage"> Storage </a>
                    </li>
                </ul>
            </header>

            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body no-padding">
                    <div class="widget-body-toolbar">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                    <input class="form-control" id="prepend" placeholder="Filter" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="removal">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th align="center">Bedrooms</th>
                                            <th>Moving Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="[[ current_quote.id == quote.id ? 'active' : '' ]] status-quote-[[quote.status]]" ng-repeat="quote in removals">
                                            <td>[[ quote.removal.customer_name ]]</td>
                                            <td>[[ quote.removal.from_postcode ]]</td>
                                            <td>[[ quote.removal.to_postcode ]]</td>
                                            <td align="center">[[ quote.removal.bedrooms ]]</td>
                                            <td>[[ quote.removal.moving_date ]]</td>
                                            <td align="right">
                                                <a class="btn btn-xs btn-danger" ng-click="removalDetails(quote)"><i class="fa fa-search"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="storage">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Pick Up</th>
                                            <th align="center">Bedrooms</th>
                                            <th>Period</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="[[ current_quote.id == quote.id ? 'active' : '' ]]" ng-repeat="quote in storages">
                                            <td>[[ quote.storage.customer_name ]]</td>
                                            <td>[[ quote.storage.pickup_postcode ]]</td>
                                            <td>[[ quote.storage.containers ]]</td>
                                            <td>[[ quote.storage.period ]]</td>
                                            <td align="right">
                                                <a class="btn btn-xs btn-danger" ng-click="storageDetails(quote)"><i class="fa fa-search"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7" ng-show="current_quote.id > 0">
        <div class="jarviswidget jarviswidget-color-blueLight" id="wid-2">
            <header>
                <h2>Removal Details</h2>
                <div class="widget-toolbar">
                    <a class="btn btn-default"><i class="fa fa-thumbs-o-down"></i></a>
                </div>
                <div class="widget-toolbar">
                    <a class="btn btn-success"><i class="fa fa-thumbs-o-up"></i></a>
                </div>
            </header>

            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body no-padding">

                    <!-- Removal details -->
                    <div class="padding-10" ng-if="current_quote.removal">
                        <div class="row lh-28">
                            <div class="col-lg-5">
                                <b>Contact Details</b>
                                <div class="row">
                                    <div class="col-lg-4">Customer</div>
                                    <div class="col-lg-8"><b>[[ current_quote.removal.customer_name ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Phone</div>
                                    <div class="col-lg-8"><b>[[ current_quote.removal.customer_phone ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Email</div>
                                    <div class="col-lg-8"><a class="text" href="mailto:[[current_quote.removal.customer_email]]"><b>[[ current_quote.removal.customer_email ]]</b></a></div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <b>Job Details</b>
                                <div class="row">
                                    <div class="col-lg-3">Pick Up</div>
                                    <div class="col-lg-3"><b>[[ current_quote.removal.from_postcode ]]</b></div>
                                    <div class="col-lg-3">Drop Off</div>
                                    <div class="col-lg-3"><b>[[ current_quote.removal.to_postcode ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">Job Date</div>
                                    <div class="col-lg-3"><b>[[ current_quote.removal.moving_date ]]</b></div>
                                    <div class="col-lg-3">Rooms</div>
                                    <div class="col-lg-3"><b>[[ current_quote.removal.bedrooms ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">Packing</div>
                                    <div class="col-lg-9"><b>[[ current_quote.removal.packing ]]</b></div>
                                </div>
                            </div>
                        </div>
                        <div class="row lh-28" ng-if="current_quote.removal.notes">
                            <div class="col-lg-5-4">Notes</div>
                            <div class="col-lg-10"><b>[[ current_quote.removal.notes ]]</b></div>
                        </div>
                    </div>

                    <!-- Storage details -->
                    <div class="padding-10" ng-if="current_quote.storage">
                        <div class="row lh-28">
                            <div class="col-lg-6">
                                <b>Contact Details</b>
                                <div class="row">
                                    <div class="col-lg-4">Customer</div>
                                    <div class="col-lg-8"><b>[[ current_quote.storage.customer_name ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Phone</div>
                                    <div class="col-lg-8"><b>[[ current_quote.storage.customer_phone ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Email</div>
                                    <div class="col-lg-8"><a class="text" href="mailto:[[current_quote.storage.customer_email]]"><b>[[ current_quote.storage.customer_email ]]</b></a></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <b>Job Details</b>
                                <div class="row">
                                    <div class="col-lg-4">Pick Up</div>
                                    <div class="col-lg-8"><b>[[ current_quote.storage.pickup_postcode ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Containers</div>
                                    <div class="col-lg-8"><b>[[ current_quote.storage.containers ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Period</div>
                                    <div class="col-lg-8"><b>[[ current_quote.storage.period ]]</b></div>
                                </div>
                            </div>
                        </div>
                        <div class="row lh-28" ng-if="current_quote.storage.notes">
                            <div class="col-lg-2">Notes</div>
                            <div class="col-lg-10"><b>[[ current_quote.storage.notes ]]</b></div>
                        </div>
                    </div>

                    <div class="widget-footer no-padding" id="map-wrapper">
                        <ui-gmap-google-map center='map.center' zoom='map.zoom'>
                            <!-- From Maker -->
                            <ui-gmap-marker coords="from_marker.coords" options="from_marker.options" events="from_marker.events" idkey="from_marker.id">
                            </ui-gmap-marker>

                            <!-- To Marker -->
                            <ui-gmap-marker coords="to_marker.coords" options="to_marker.options" events="to_marker.events" idkey="to_marker.id">
                            </ui-gmap-marker>

                            <!-- Path -->
                            <ui-gmap-polyline ng-repeat="p in paths" path="p.path" stroke="p.stroke" visible='p.visible' geodesic='p.geodesic' fit="false" editable="p.editable" draggable="p.draggable" icons='p.icons'></ui-gmap-polyline>
                        </ui-gmap-google-map>
                    </div>
                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </div>
</div>

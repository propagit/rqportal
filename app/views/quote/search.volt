
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-text-o"></i> Search Quotes</h1>
    </div>
</div>

<div class="row" ng-controller="QuoteCtrl">
    <div class="col-lg-5">
        <div class="jarviswidget jarviswidget-no-header" id="wid-1">
            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body no-padding">
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
                                <tr class="[[ active_quote.id == quote.id ? 'active' : '' ]]" ng-repeat="quote in quotes">
                                    <td>[[ quote.customer_name ]]</td>
                                    <td>[[ quote.from_postcode ]]</td>
                                    <td>[[ quote.to_postcode ]]</td>
                                    <td align="center">[[ quote.bedrooms ]]</td>
                                    <td>[[ quote.moving_date ]]</td>
                                    <td align="right">
                                        <a class="btn btn-xs btn-danger" ng-click="quoteDetails(quote)"><i class="fa fa-search"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7" ng-show="active_quote.id > 0">
        <div class="jarviswidget jarviswidget-color-blueLight" id="wid-2">
            <header>
                <h2>Job Details</h2>
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
                    <div class="padding-10">
                        <div class="row lh-28">
                            <div class="col-lg-5">
                                <b>Contact Details</b>
                                <div class="row">
                                    <div class="col-lg-4">Customer</div>
                                    <div class="col-lg-8"><b>[[ active_quote.customer_name ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Phone</div>
                                    <div class="col-lg-8"><b>[[ active_quote.customer_phone ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">Email</div>
                                    <div class="col-lg-8"><a class="text" href="mailto:[[active_quote.customer_email]]"><b>[[ active_quote.customer_email ]]</b></a></div>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <b>Job Details</b>
                                <div class="row">
                                    <div class="col-lg-3">Pick Up</div>
                                    <div class="col-lg-3"><b>[[ active_quote.from_postcode ]]</b></div>
                                    <div class="col-lg-3">Drop Off</div>
                                    <div class="col-lg-3"><b>[[ active_quote.to_postcode ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">Job Date</div>
                                    <div class="col-lg-3"><b>[[ active_quote.moving_date ]]</b></div>
                                    <div class="col-lg-3">Rooms</div>
                                    <div class="col-lg-3"><b>[[ active_quote.bedrooms ]]</b></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">Packing</div>
                                    <div class="col-lg-9"><b>[[ active_quote.packing ]]</b></div>
                                </div>
                            </div>
                        </div>
                        <div class="row lh-28" ng-if="active_quote.notes">
                            <div class="col-lg-5-4">Notes</div>
                            <div class="col-lg-10"><b>[[ active_quote.notes ]]</b></div>
                        </div>
                    </div>

                    <div class="widget-footer no-padding">
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

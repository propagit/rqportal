<div ng-controller="QuoteCtrl">

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-text-o"></i> Search Quotes <small>> Search and find quotes in the system</small></h1>
    </div>
</div>
<form class="form-horizontal">
    {% if elements.isAdmin() %}
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon"><span class="radio">
                    <label>
                        <input type="radio" class="radiobox style-0" ng-model="params.allocated" value="located">
                        <span> &nbsp; <i class="fa fa-user"></i></span>
                    </label>
                </span></span>
                <angucomplete-alt id="pickup"
                              minlength="1"
                              placeholder="Enter supplier name, company, business to search..."
                              pause="400"
                              selected-object="params.supplier"
                              remote-url="/rqportal/supplierAjax/search/"
                              remote-url-data-field="suppliers"
                              title-field="name"
                              description-field="business"
                              input-class="form-control"
                              match-class="highlight"
                              field-required="true" disable-input="params.allocated == 'not_allocated'"></angucomplete-alt>
                <span class="input-group-addon">
                    <span class="radio">
                        <label>
                            <input type="radio" class="radiobox style-0" ng-model="params.allocated" value="not_allocated">
                            <span> &nbsp; Not allocated</span>
                        </label>
                    </span>
                </span>
            </div>
        </div>
    </div>
    {% endif %}
    <div class="form-group">
        <div class="col-md-3">
            <div class="input-group">
                <ng-bs3-datepicker data-ng-model='params.from_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="From Date" required></ng-bs3-datepicker>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <ng-bs3-datepicker data-ng-model='params.to_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="To Date" required></ng-bs3-datepicker>
            </div>
        </div>
    </div>

    {% if elements.isAdmin() %}
    <div class="form-group">
        <div class="col-md-3">
            <?php echo $this->tag->selectStatic(array(
                'state', State::find(),
                'useEmpty'  => true,
                'emptyText' => 'Select State',
                'using' => array('code', 'name'),
                'class' => 'form-control',
                'ng-model' => 'params.state'
            )); ?>
        </div>
        <div class="col-md-3">
            <?php echo $this->tag->selectStatic(array(
                'status', Quote::getStatus(),
                'class' => 'form-control',
                'ng-model' => 'params.status'
            )); ?>
        </div>
    </div>
    {% endif %}
</form>

<div class="row">
    <section class="col-lg-6">
        <a class="btn btn-labeled btn-danger" ng-click="searchQuotes(params)">
            <span class="btn-label"><i class="fa fa-search"></i></span>Search
        </a>
    </section>
</div>

<div class="section-divider"></div>

<div class="alert alert-warning" ng-if="removals.length == 0 && storages.length == 0">
    There are no quotes
</div>

<div class="row">
    <div class="col-lg-5">
        <div class="jarviswidget" id="wid-1" ng-if="removals.length > 0 || storages.length > 0">

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
                                    <input class="form-control" ng-model="keyword" placeholder="Search by customer name, postcode or moving date" type="text">
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
                                        <tr class="[[ current_quote.id == quote.id ? 'active' : '' ]] status-quote-[[ quote.status ]]" ng-repeat="quote in removals | filter: keyword">
                                            <td>[[ quote.removal.customer_name ]]</td>
                                            <td>[[ quote.removal.from_postcode ]]</td>
                                            <td>[[ quote.removal.to_postcode ]]</td>
                                            <td align="center">[[ quote.removal.bedrooms ]]</td>
                                            <td>[[ quote.removal.moving_date ]]</td>
                                            <td align="right">
                                                <a ng-if="current_quote.id != quote.id" class="btn btn-xs btn-danger" ng-click="removalDetails(quote)"><i class="fa fa-search"></i></a>
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
                                        <tr class="[[ current_quote.id == quote.id ? 'active' : '' ]] status-quote-[[ quote.status ]]" ng-repeat="quote in storages | filter: keyword">
                                            <td>[[ quote.storage.customer_name ]]</td>
                                            <td>[[ quote.storage.pickup_postcode ]]</td>
                                            <td>[[ quote.storage.containers ]]</td>
                                            <td>[[ quote.storage.period ]]</td>
                                            <td align="right">
                                                <a ng-if="current_quote.id != quote.id" class="btn btn-xs btn-danger" ng-click="storageDetails(quote)"><i class="fa fa-search"></i></a>
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
        <div class="jarviswidget" id="wid-2">
            <header>
                <h2>Removal Details</h2>
                {% if not elements.isAdmin() %}
                <div class="widget-toolbar">
                    <a class="btn btn-[[ current_quote.status == 2 ? 'danger' : 'default' ]]" rel="popover-hover" data-placement="top" data-original-title="Mark as Lost" data-content="Mark this job as 'Lost' so you can keep track of the jobs you lost." ng-click="updateQuoteStatus(current_quote.id, 2)"><i class="fa fa-thumbs-o-down"></i></a>
                </div>
                <div class="widget-toolbar">
                    <a class="btn btn-[[ current_quote.status == 3 ? 'success' : 'default' ]]" class="btn btn-default btn-lg" rel="popover-hover" data-placement="top" data-original-title="Mark as Won" data-content="Mark this job as 'Won' so you can keep track of the jobs you win." ng-click="updateQuoteStatus(current_quote.id, 3)"><i class="fa fa-thumbs-o-up"></i></a>
                </div>
                {% endif %}

                <ul id="widget-tab-3" class="nav nav-tabs pull-right">
                    <li class="active">
                        <a data-toggle="tab" href="#details"> Quote Details </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#suppliers"> Suppliers </a>
                    </li>
                </ul>
            </header>

            <!-- widget div-->
            <div>
                <!-- widget content -->
                <div class="widget-body no-padding">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="details">
                            <!-- Removal details -->
                            <div class="padding-10" ng-show="current_quote.removal">
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
                        <div class="tab-pane fade" id="suppliers">
                            <div class="widget-body-toolbar">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                            <input class="form-control" id="prepend" placeholder="Search supplier" type="text">
                                            <a class="input-group-addon">
                                                <i class="fa fa-plus"></i> &nbsp; Send Quote
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div ng-show="current_quote.suppliers.length == 0">
                                <div class="alert alert-warning">
                                    This quote has not been sent to any suppliers.
                                </div>
                            </div>
                            <div class="table-responsive" ng-show="current_quote.suppliers.length > 0">

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Supplier</th>
                                            <th>Company</i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="supplier in current_quote.suppliers" class="status-quote-[[ supplier.quote_status ]]">
                                            <td>[[ supplier.name ]]</td>
                                            <td>[[ supplier.business ]]</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </div>
</div>

</div>


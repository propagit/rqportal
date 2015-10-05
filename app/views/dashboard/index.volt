<div ng-controller="DashboardCtrl">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-dashboard"></i> Dashboard</h1>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <div class="pull-right smart-form" id="stats-option">
                <section>
                    <label class="select">
                        <select ng-model="time">
                            <option value="month">This Month</option>
                            <option value="all">All Time</option>
                        </select> <i></i> </label>
                </section>
            </div><!-- /btn-group -->
            <ul id="sparks" class="" ng-if="stats">
                <li class="sparks-info">
                    <h5> Income <span class="txt-color-blue">[[ stats.income | currency ]]</span></h5>
                </li>
                <li class="sparks-info">
                    <a ng-if="stats.unpaid_invoice > 0" href="{{ baseUrl }}billing/invoice?q=unpaid" class="badge bg-color-red">[[ stats.unpaid_invoice ]]</a>

                    <h5> Invoice <span class="txt-color-greenDark"><i class="fa fa-file-text-o"></i>&nbsp;[[ stats.total_invoice ]]</span></h5>
                </li>
                <li class="sparks-info">
                    <a ng-if="stats.unallocated_quote > 0" href="{{ baseUrl }}quote?q=un-allocated" class="badge bg-color-red">[[ stats.unallocated_quote ]]</a>

                    <h5> Quotes <span class="txt-color-purple"><i class="fa fa-comment-o"></i>&nbsp;[[ stats.total_quotes ]]</span></h5>
                </li>
                <li class="sparks-info">
                    <a ng-if="stats.incompleted_supplier > 0" href="{{ baseUrl }}supplier?q=incomplete" class="badge bg-color-red">[[ stats.incompleted_supplier ]]</a>

                    <h5> Supplier <span class="txt-color-greenDark"><i class="fa fa-user"></i>&nbsp;[[ stats.total_suppliers ]]</span></h5>
                </li>

            </ul>

        </div>
    </div>

    <div class="row">

        <div class="col-sm-12">
        <!-- new widget -->
            <div class="jarviswidget" id="wid-id-3" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                <header>
                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                    <h2>Sales Chart </h2>
                </header>

                <!-- widget div-->

                <div class="widget-body padding-10">
                    <canvas id="chart-sales" class="chart chart-line" data="data"
                      labels="labels" legend="true" series="series" options="options"
                      click="onClick" height="100">
                    </canvas>
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </div>
    </div>
    
    <div class="section-divider"></div>
    
    <div class="alert alert-warning" ng-hide="duplicateRemovalsCount">
        There are no duplicate quotes
    </div>
    
    <!-- duplicate quotes -->
    <div class="row" ng-init="getDuplicateQuotes();" ng-show="duplicateRemovalsCount">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-comment-o"></i> Duplicated Quotes 
                <span>>
                    The below quotes appear to be duplicates
                </span>
            </h1>
        </div>
        <div class="col-md-[[ current_quote.id ? 5 : 12 ]]">
            <div class="jarviswidget" id="wid-1" ng-if="duplicateRemovalsCount > 0">
    
                <header>
                    <ul id="widget-tab-1" class="nav nav-tabs pull-left">
                        <li class="active" ng-if="duplicateRemovalsCount> 0">
                            <a data-toggle="tab" href="#removal"> Removal
                                <span class="badge">[[ duplicateRemovalsCount ]]</span>
                            </a>
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
                            <div class="tab-pane fade in active">
    
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Customer</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th class="text-center">Bedrooms</th>
                                                <th>Moving Date</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody ng-repeat="removal in duplicateRemovals | filter: keyword" style="border-top:1px solid #ddd;">
                                            <tr>
                                                <td>[[ removal.customer_name ]]</td>
                                                <td>[[ removal.is_international == 'no' ? removal.from_postcode : removal.from_country ]]</td>
                                                <td>[[ removal.is_international == 'no' ? removal.to_postcode : removal.to_country]]</td>
                                                <td align="center">[[ removal.bedrooms ]]</td>
                                                <td>[[ removal.moving_date | date: 'dd MMM yyyy' ]]</td>
                                                <td>
                                                    <button class="btn btn-xs btn-muted" style="color:#fff;"><i class="fa fa-check"></i> Distributed</button>
                                                    <a ng-if="current_quote.id != removal.id" class="btn btn-xs btn-danger" ng-click="removalDetails(removal,removal)"><i class="fa fa-search"></i></a>
                                                </td>
                                            </tr>
                                            <tr nf-if="removal.duplicates.length > 0" ng-repeat="duplicate in removal.duplicates" class="text-muted">
                                                <td>[[ duplicate.customer_name ]]</td>
                                                <td>[[ duplicate.is_international == 'no' ? duplicate.from_postcode : duplicate.from_country ]]</td>
                                                <td>[[ duplicate.is_international == 'no' ? duplicate.to_postcode : duplicate.to_country]]</td>
                                                <td align="center">[[ duplicate.bedrooms ]]</td>
                                                <td>[[ duplicate.moving_date | date: 'dd MMM yyyy' ]]</td>
                                                <td>
                                                    <button class="btn btn-xs btn-success" ng-click="reSend(duplicate);">Re Send</button>
                                                    <button class="btn btn-xs btn-danger" ng-click="confirmDelete(duplicate);"><i class="fa fa-trash"></i></button>
                                                    <a ng-if="current_quote.id != duplicate.id" class="btn btn-xs btn-danger" ng-click="removalDetails(duplicate,removal)"><i class="fa fa-search"></i></a>
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
    
        <div class="col-md-7" ng-show="current_quote.id">
            <div class="jarviswidget" id="wid-2">
                <header>
                  
                    <ul id="widget-tab-3" class="nav nav-tabs pull-right">
                        <li class="active">
                            <a data-toggle="tab" href="#details"> Removal Details </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#suppliers"> Suppliers
                                <span class="badge">[[ current_quote.suppliers.length ]]</span>
                            </a>
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
                                <div class="padding-10">
                                    <div class="row lh-28">
                                        <div class="col-lg-5">
                                            <b>Contact Details</b>
                                            <div class="row">
                                                <div class="col-lg-4">Customer</div>
                                                <div class="col-lg-8"><b>[[ current_quote.customer_name ]]</b></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">Phone</div>
                                                <div class="col-lg-8"><b>[[ current_quote.customer_phone ]]</b></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">Email</div>
                                                <div class="col-lg-8"><a class="text" href="mailto:[[current_quote.removal.customer_email]]"><b>[[ current_quote.customer_email ]]</b></a></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <b>Job Details</b>
                                            <div class="row">
                                                <div class="col-lg-3">Pick Up</div>
                                                <div class="col-lg-3"><b>[[ current_quote.is_international == 'no' ? current_quote.from_postcode : current_quote.from_country ]]</b></div>
                                                <div class="col-lg-3">Drop Off</div>
                                                <div class="col-lg-3"><b>[[ current_quote.is_international == 'no' ? current_quote.to_postcode : current_quote.to_country ]]</b></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3">Job Date</div>
                                                <div class="col-lg-3"><b>[[ current_quote.moving_date | date: 'd MMM yy' ]]</b></div>
                                                <div class="col-lg-3">Bedrooms</div>
                                                <div class="col-lg-3"><b>[[ current_quote.bedrooms ]]</b></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3">Packing</div>
                                                <div class="col-lg-9"><b>[[ current_quote.packing ]]</b></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row lh-28" ng-if="current_quote.notes">
                                        <div class="col-lg-5-4">Notes</div>
                                        <div class="col-lg-10"><b>[[ current_quote.notes ]]</b></div>
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
    
    
                            {% if elements.isAdmin() %}
                            <div class="tab-pane fade" id="suppliers">
                                <div class="widget-body-toolbar">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                                <angucomplete-alt id="supplier"
                                                  minlength="1"
                                                  placeholder="Enter supplier name, company to search..."
                                                  pause="400"
                                                  selected-object="new_supplier"
                                                  remote-url="{{ baseUrl }}supplierajax/search/"
                                                  remote-url-data-field="suppliers"
                                                  title-field="name"
                                                  description-field="business"
                                                  input-class="form-control"
                                                  match-class="highlight"
                                                  field-required="true"
                                                  disable-input="all_suppliers == 'YES'"></angucomplete-alt>
    
                                                <span class="input-group-addon">
                                                    Free &nbsp;
                                                    <span class="onoffswitch">
                                                        <input type="checkbox" ng-model="free" ng-true-value="'YES'" ng-false-value="'NO'" name="start_interval" class="onoffswitch-checkbox" id="st3">
                                                        <label class="onoffswitch-label" for="st3">
                                                            <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </span>
                                                </span>
    
                                               
                                                <a class="input-group-addon" ng-click="addSupplier(new_supplier, free)">
                                                    <i class="fa fa-plus"></i> &nbsp; Send Quote
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="padding-10" ng-show="current_quote.suppliers.length == 0">
                                    <div class="alert alert-warning">
                                        This quote has not been sent to any suppliers.
                                    </div>
                                </div>
                                <div class="padding-10" ng-if="error">
                                    <div class="alert alert-danger">
                                        [[ error ]]
                                    </div>
                                </div>
                                <div class="table-responsive no-padding" ng-show="current_quote.suppliers.length > 0">
    
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
                            {% endif %}
    
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
<!-- confirm Delete -->
<script type="text/ng-template" id="confirmDeleteDuplicate.html">
<div class="modal-body">
  	<p class="text-danger"><i class="fa fa-exclamation-triangle"></i> Confirm  Delete?</p>
</div>
<div class="modal-footer">
	<button class="btn btn-danger" ng-click="deleteDuplicate()"><i class="fa fa-trash"></i> Delete</button>
	<button class="btn btn-default" ng-click="cancel()">Cancel</button>
</div>
</script>


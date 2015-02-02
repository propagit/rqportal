<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-text-o"></i> Manage Billing <small>> Outstanding Quotes</small></h1>
    </div>
</div>

<div class="row" ng-controller="BillingQuoteCtrl">
    <div class="col-lg-[[ quotes.length > 0 ? 6 : 12 ]]" ng-if="suppliers.length > 0">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                <h2>Suppliers</h2>

            </header>

            <!-- widget div-->
            <div>

                <!-- widget edit box -->
                <div class="jarviswidget-editbox">
                    <!-- This area used as dropdown edit box -->

                </div>
                <!-- end widget edit box -->

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

                        <thead>
                            <tr>
                                <th data-class="expand">Name</th>
                                <th>Business</th>
                                <th>Outstanding Quotes</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="supplier in suppliers">
                                <td>[[ supplier.name ]]</td>
                                <td>[[ supplier.business ]]</td>
                                <td>[[ supplier.quotes ]]</td>
                                <td>
                                    <a ng-if="supplier.user_id != current_user_id" ng-click="listQuotes(supplier.user_id)" class="btn btn-xs btn-primary">List <i class="fa fa-chevron-right"></i></a>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </div>

    <div class="col-lg-6" ng-if="quotes.length > 0">

        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                <h2>Outstanding Quotes</h2>
                <ul id="widget-tab-3" class="nav nav-tabs pull-right">
                    <li>
                        <a ng-click="generateInvoice([[ current_user_id ]])"><i class="fa fa-plus"></i> &nbsp; Generate Invoice</a>
                    </li>
                </ul>
            </header>

            <!-- widget div-->
            <div>

                <!-- widget content -->
                <div class="widget-body no-padding">

                    <table id="datatable_fixed_column" class="table table-bordered" width="100%">

                        <thead>
                            <tr>
                                <th data-class="expand">Type</th>
                                <th>Customer</th>
                                <th>Postcode</th>
                                <th>Sent On</th>
                                <th width="30"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="quote in quotes" class="status-quote-[[ quote.status ]]">
                                <td>[[ quote.job_type ]]</td>
                                <td>[[ quote.customer_name ]]</td>
                                <td>[[ quote.postcode ]]</td>
                                <td>[[ quote.created_on ]]</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-right">
                                            <li>
                                                <a ng-click="deleteQuote([[quote.id]])">Delete this quote</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>
                <!-- end widget content -->

            </div>
            <!-- end widget div -->

        </div>
        <!-- end widget -->
    </div>
</div>

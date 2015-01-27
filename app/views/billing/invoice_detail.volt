<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget well jarviswidget-color-darken" id="wid-id-4" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-barcode"></i> </span>
                    <h2>Item #44761 </h2>

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

                        <div class="widget-body-toolbar">

                            <div class="row">

                                <div class="col-sm-4">

                                    <div class="input-group">
                                        <input class="form-control" type="text" placeholder="Type invoice number or date...">
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="button">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-8 text-align-right">

                                    <div class="btn-group">
                                        <a ng-click="listInvoices()" class="btn btn-sm btn-default">
                                            <i class="fa fa-chevron-left"></i> Back to List</a>
                                        </a>
                                    </div>
                                    &nbsp;
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-info">
                                            <i class="fa fa-download"></i> Download
                                        </a>
                                    </div>
                                    &nbsp;
                                    <div class="btn-group">
                                        <a class="btn btn-sm btn-info">
                                            <i class="fa fa-envelope-o"></i> Email
                                        </a>
                                    </div>
                                    <!-- &nbsp;
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-success"> <i class="fa fa-plus"></i> Create New </a>
                                    </div> -->


                                </div>

                            </div>

                        </div>

                        <div class="padding-10">
                            <br>
                            <div class="pull-left">
                                <img src="{{ baseUrl }}img/logo.png" width="150" alt="Removalist Quote">

                                <address>
                                    <br>
                                    <strong>Removalist Quote Pty. LTD</strong>
                                    <br>
                                    ABN: 42 155 562 959<br>
                                    <br>
                                    Head Office<br>
                                    P.O. Box 1172<br>
                                    Bentleigh East - VIC 3165<br>
                                    Tel: 1300 531 475
                                </address>
                            </div>
                            <div class="pull-right">
                                <h1 class="font-400">tax invoice</h1>
                                <div class="badge-paid" ng-if="current_invoice.status == {{ constant("Invoice::PAID") }}">
                                    <img src="{{ baseUrl}}img/badge-paid.png" />
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-sm-9">
                                    <h4 class="semi-bold">[[ current_invoice.supplier.business ]]</h4>
                                    <address>
                                        <strong>[[ current_invoice.supplier.name ]]</strong>
                                        <br>
                                        [[ current_invoice.supplier.address ]]
                                        <br>
                                        [[ current_invoice.supplier.suburb ]] - [[ current_invoice.supplier.state ]] [[ current_invoice.supplier.postcode ]]
                                        <br>
                                        Tel: [[ current_invoice.supplier.phone ]]
                                    </address>
                                </div>
                                <div class="col-sm-3">
                                    <div>
                                        <div>
                                            <strong>INVOICE NO :</strong>
                                            <span class="pull-right"> #[[ current_invoice.id ]] </span>
                                        </div>

                                    </div>
                                    <div>
                                        <div class="font-md">
                                            <strong>INVOICE DATE :</strong>
                                            <span class="pull-right"> <i class="fa fa-calendar"></i> [[ current_invoice.created_on | date: 'dd/MM/yyyy']] </span>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="well well-sm  bg-color-darken txt-color-white no-border">
                                        <div class="fa-lg">
                                            Total Due :
                                            <span class="pull-right"> [[ current_invoice.amount | currency ]] </span>
                                        </div>

                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>QTY</th>
                                        <th>ITEM COST</th>
                                        <th>DESCRIPTION</th>
                                        <th class="text-right">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>[[ current_invoice.removals.length + current_invoice.storages.length ]]</strong></td>
                                        <td>$10.00</td>
                                        <td>Quotes Received (Full breakdown of received quotes below)</td>

                                        <td align="right"><strong>[[ current_invoice.amount | currency ]]</strong></td>
                                    </tr>
                                    <tr>
                                        <td>GST</td>
                                        <td colspan="2">10%</td>
                                        <td align="right">[[ current_invoice.amount/11 | currency ]]</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">SUBTOTAL</td>
                                        <td align="right">[[ current_invoice.amount * 10/11 | currency ]]</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="invoice-footer">

                                <div class="row">

                                    <div class="col-sm-7">
                                        <div class="payment-methods">
                                            <h5>Payment Methods</h5>
                                            <img src="{{ baseUrl }}img/mastercard.png" width="64" height="64" alt="mastercard">
                                            <img src="{{ baseUrl }}img/visa.png" width="64" height="64" alt="visa">
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-5">
                                        <div class="invoice-sum-total pull-right">
                                            <h3><strong>Total: <span class="text-success">[[ current_invoice.amount | currency ]]</span></strong></h3>
                                        </div>
                                    </div> -->

                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <p class="note">**To avoid any excess penalty charges, please make payments within 30 days of the due date. There will be a 2% interest charge per month on all late invoices.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

            <div class="jarviswidget well jarviswidget-color-darken" id="wid-id-5" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                <div class="padding-10">
                    <address>
                        <br>
                        <strong>Removalist Quote Pty. LTD<br>
                        Service Breakdown</strong>
                        <br>
                        Invoice # [[ current_invoice.id ]]
                    </address>
                    <div ng-if="current_invoice.removals.length > 0">
                        <h6>Removal Quotes</h6>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <th>FROM</th>
                                    <th>TO</th>
                                    <th class="text-center">ROOMS</th>
                                    <th>DATE</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-right">COST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="removal in current_invoice.removals">
                                    <td><strong>[[ removal.customer_name ]]</strong></td>
                                    <td>[[ removal.from_postcode ]]</td>
                                    <td>[[ removal.to_postcode ]]</td>
                                    <td align="center">[[ removal.bedrooms ]]</td>
                                    <td>[[ removal.created_on | date : 'dd MMM yyyy' ]]</td>
                                    <td align="center">
                                        [[ removal.status == {{ constant("Quote::WON") }} ? 'Won' : (removal.status == {{ constant("Quote::LOST") }} ? 'Lost' : 'Open') ]]</td>
                                    <td align="right">$10.00</td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td colspan="6" align="right"><strong>[[ current_invoice.removals.length * 10 | currency ]]</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div ng-if="current_invoice.storages.length > 0">
                        <h6>Storage Quotes</h6>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>CUSTOMER</th>
                                    <th>PICKUP</th>
                                    <th class="text-center">CONTAINERS</th>
                                    <th>PERIOD</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-right">COST</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="storage in current_invoice.storages">
                                    <td><strong>[[ storage.customer_name ]]</strong></td>
                                    <td>[[ storage.pickup_postcode ]]</td>
                                    <td align="center">[[ storage.containers ]]</td>
                                    <td>[[ storage.created_on | date : 'dd MMM yyyy' ]]</td>
                                    <td align="center">[[ storage.status == {{ constant("Quote::WON") }} ? 'Won' : 'Lost' ]]</td>
                                    <td align="right">$10.00</td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td colspan="6" align="right"><strong>[[ current_invoice.storages.length * 10 | currency ]]</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </article>
        <!-- WIDGET END -->

    </div>

    <!-- end row -->

</section>
<!-- end widget grid -->

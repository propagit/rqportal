<div ng-controller="CreateInvoiceCtrl">
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget well jarviswidget-color-darken" id="wid-id-4" data-widget-sortable="false" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-colorbutton="false">
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <div class="widget-body-toolbar">
                            <div class="row">
                                <div class="col-sm-5">

                                    <div class="input-group[[ no_supplier ? ' has-error' : '' ]]">
                                        <angucomplete-alt id="pickup"
                                          minlength="1"
                                          placeholder="Enter supplier name, company to invoice..."
                                          pause="400"
                                          selected-object="invoice_supplier"
                                          remote-url="{{ baseUrl }}supplierajax/search/"
                                          remote-url-data-field="suppliers"
                                          title-field="name"
                                          description-field="business"
                                          input-class="form-control"
                                          match-class="highlight"
                                          field-required="true"></angucomplete-alt>
                                        <div class="input-group-btn">
                                            <button class="btn btn-default" ng-click="loadInvoice(invoice_id)" type="button">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-7 text-align-right">
                                    <div class="btn-group">
                                        <a class="btn btn-labeled btn-success" ng-click="saveInvoice()">
                                            <span class="btn-label"><i class="fa fa-plus"></i></span>
                                            Save New Invoice </a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="padding-10">
                            <div ng-if="error" class="alert alert-danger">
                                [[ error ]]
                            </div>
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
                            </div>
                            <div class="clearfix"></div>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-sm-8">
                                    <h4 class="semi-bold">[[ supplier.business ]]</h4>
                                    <address>
                                        <strong>[[ supplier.name ]]</strong>
                                        <br>
                                        [[ supplier.address ]]
                                        <br>
                                        [[ supplier.suburb ]] - [[ supplier.state ]] [[ supplier.postcode ]]
                                        <br>
                                        Tel: [[ supplier.phone ]]
                                    </address>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <strong>INVOICE NO :</strong>
                                        </div>
                                        <div class="col-sm-7 invoice-input">
                                            <div class="input-group">
                                                <span class="input-group-addon">#</span>
                                                <input type="text" class="form-control" placeholder="Auto-generated" disabled >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <strong>INVOICE DATE :</strong>
                                        </div>
                                        <div class="col-sm-7 invoice-input">
                                            <ng-bs3-datepicker data-ng-model='billed_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="From Date" required></ng-bs3-datepicker>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <strong>DUE DATE :</strong>
                                        </div>
                                        <div class="col-sm-7 invoice-input">
                                            <ng-bs3-datepicker data-ng-model='due_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="From Date" required></ng-bs3-datepicker>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="well well-sm  bg-color-darken txt-color-white no-border">
                                        <div class="fa-lg">
                                            Total Due :
                                            <span class="pull-right"> [[ amount | customCurrency ]] </span>
                                        </div>

                                    </div>
                                    <br>
                                    <br>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="120">QTY</th>
                                        <th width="120">ITEM COST</th>
                                        <th>DESCRIPTION</th>
                                        <th width="120" class="text-right">TOTAL</th>
                                        <th width="10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="line in lines">
                                        <td><strong>[[ line.qty ]]</strong></td>
                                        <td>[[ line.cost | customCurrency ]]</td>
                                        <td>[[ line.description ]]</td>
                                        <td align="right"><strong>[[ line.qty * line.cost | customCurrency ]]</strong></td>
                                        <td>
                                            <a ng-click="deleteLine($index)" class="btn btn-xs btn-danger"><i class="fa fa-minus"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="hasinput[[ error_qty ? ' has-error' : '' ]]">
                                            <input type="text" class="form-control" ng-model="line.qty" placeholder="* Required" required>

                                        </td>
                                        <td class="hasinput[[ error_cost ? ' has-error' : '' ]]">
                                            <input type="text" class="form-control" ng-model="line.cost" placeholder="* Required" required>
                                        </td>
                                        <td class="hasinput[[ error_description ? ' has-error' : '' ]]">
                                            <input type="text" class="form-control" ng-model="line.description" placeholder="* Required" required>
                                        </td>

                                        <td align="right">
                                            <input type="text" class="form-control" ng-value="line.qty && line.cost ? line.qty * line.cost : ''" />
                                        </td>
                                        <td>
                                            <a ng-disabled="lineForm.$invalid" ng-click="addLine(line)" class="btn btn-xs btn-invoice btn-success"><i class="fa fa-plus"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5"></td>
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
        </article>
        <!-- WIDGET END -->

    </div>

    <!-- end row -->

</section>
<!-- end widget grid -->
</div>

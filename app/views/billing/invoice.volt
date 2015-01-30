<div ng-controller="BillingInvoiceCtrl">

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-file-text-o"></i> Manage Billing <small>> Search invoices created in the system</small></h1>
    </div>
</div>


<div ng-show="!current_invoice.id">

    {% include 'billing/search.volt' %}

    <div class="section-divider"></div>

    <div class="row">
        <div class="col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>Invoices</h2>

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
                                    <th data-class="expand">Invoice #</th>
                                    <th><i class="fa fa-user"></i> Supplier</th>
                                    <th><i class="fa fa-calendar"></i> Billed Date</th>
                                    <th><i class="fa fa-dollar"></i> Amount</th>
                                    <th width="80">Status</th>
                                    <th><i class="fa fa-calendar"></i> Paid On</th>
                                    <th width="268"></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr ng-repeat="invoice in invoices">
                                    <td align="center">[[ invoice.id ]]</td>
                                    <td><b>[[ invoice.supplier.name ]]</b> - [[ invoice.supplier.business ]]</td>
                                    <td>[[ invoice.created_on | date:'dd/MM/yyyy' ]]</td>
                                    <td class="text-[[ invoice.status == {{ constant("Invoice::UNPAID") }} ? 'warning' : 'success' ]]">[[ invoice.amount | currency ]]</td>
                                    <td>
                                        <div class="btn-group" ng-if="invoice.status == {{ constant("Invoice::UNPAID") }}">
                                            <button class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown">
                                                Unpaid <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a ng-click="processInvoice([[ invoice.id ]])">Process</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <span class="btn btn-xs btn-block btn-success" ng-if="invoice.status == {{ constant("Invoice::PAID") }}"><i class="fa fa-check"></i>  Paid</span>
                                    </td>
                                    <td>[[ invoice.paid_on ]]</td>
                                    <td width="261">
                                        <a ng-click="viewInvoice([[ $index ]])" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>
                                        &nbsp;
                                        <a class="btn btn-xs btn-info"><i class="fa fa-envelope-o"></i> Email</a>
                                        &nbsp;
                                        <a href="{{ baseUrl }}billing/download/[[ invoice.id ]]" class="btn btn-xs btn-info"><i class="fa fa-download"></i> Download</a>
                                        &nbsp;
                                        <div class="btn-group">
                                            <button class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-times"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-right">
                                                <li>
                                                    <a ng-click="deleteInvoice([[invoice.id]])">Delete this invoice</a>
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
</div>


<div ng-if="current_invoice.id">
    {% include 'billing/invoice_detail.volt' %}
</div>

</div>

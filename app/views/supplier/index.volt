<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-user"></i> Supplier
            <span>>
                Search Suppliers
            </span>
        </h1>
    </div>
</div>

<div ng-controller="SupplierCtrl">
    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
        <!-- widget options:
        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

        data-widget-colorbutton="false"
        data-widget-editbutton="false"
        data-widget-togglebutton="false"
        data-widget-deletebutton="false"
        data-widget-fullscreenbutton="false"
        data-widget-custombutton="false"
        data-widget-collapsed="true"
        data-widget-sortable="false"

        -->
        <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Suppliers </h2>

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

                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
                            <th>Business</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-map-marker txt-color-blue hidden-md hidden-sm hidden-xs"></i>Address</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i>Phone</th>
                            <th data-hide="phone,tablet">Email</th>
                            <th data-hide="phone,tablet">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="supplier in suppliers">
                            <td>[[ supplier.name ]]</td>
                            <td>[[ supplier.business ]]</td>
                            <td>[[ supplier.address ]]</td>
                            <td>[[ supplier.phone ]]</td>
                            <td>[[ supplier.email ]]</td>
                            <td>
                                <span ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}" class="text-warning">Applied</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::ACTIVATED") }}" class="text-primary">Activated</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::APPROVED") }}" class="text-success">Approved</span>
                            </td>
                            <td>
                                <a ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}"  href="{{ baseUrl }}supplier/activate/[[ supplier.id ]]" class="btn btn-xs btn-warning">Activate</a>
                                &nbsp;
                                <a ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}" ng-click="reject([[ $index ]])" class="btn btn-xs btn-danger">Reject</a>


                                <a ng-if="supplier.status == {{ constant("Supplier::ACTIVATED") }}" href="{{ baseUrl }}applicant/register/[[ supplier.id ]]/[[ supplier.activation_key ]]" class="btn btn-xs btn-primary">Complete Profile</a>

                                <a ng-if="supplier.status == {{ constant("Supplier::APPROVED") }}" href="{{ baseUrl }}supplier/login/[[ supplier.user_id ]]" class="btn btn-xs btn-success">Login as supplier</a>
                                &nbsp;
                                <div class="btn-group">
                                    <button class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-right">
                                        <li ng-if="supplier.status > {{ constant("Supplier::ACTIVATED") }}">
                                            <a ng-click="deactivate([[ $index ]])">De-activate</a>
                                        </li>
                                        <li>
                                            <a ng-click="delete([[ $index ]])">Delete this supplier</a>
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



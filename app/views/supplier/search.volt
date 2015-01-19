<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-user"></i> Supplier
            <span>>
                Search Suppliers
            </span>
        </h1>
    </div>
</div>

<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-red" id="wid-id-1" data-widget-editbutton="false">
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
                    <!-- <tr>
                        <th class="hasinput" style="width:17%">
                            <input type="text" class="form-control" placeholder="Filter Name" />
                        </th>
                        <th class="hasinput" style="width:18%">
                            <div class="input-group">
                                <input class="form-control" placeholder="Filter Position" type="text">
                                <span class="input-group-addon">
                                    <span class="onoffswitch">
                                        <input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="st3">
                                        <label class="onoffswitch-label" for="st3">
                                            <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </span>
                                </span>
                            </div>


                        </th>
                        <th class="hasinput" style="width:16%">
                            <input type="text" class="form-control" placeholder="Filter Office" />
                        </th>
                        <th class="hasinput" style="width:17%">
                            <input type="text" class="form-control" placeholder="Filter Age" />
                        </th>
                        <th class="hasinput">
                            <input type="text" class="form-control" placeholder="Filter Salary" />
                        </th>
                        <th class="hasinput" style="width:16%">
                            <input type="text" class="form-control" placeholder="Filter Salary" />
                        </th>
                    </tr> -->
                    <tr>
                        <th data-class="expand">Name</th>
                        <th>Business</th>
                        <th data-hide="phone">Address</th>
                        <th data-hide="phone">Phone</th>
                        <th data-hide="phone,tablet">Email</th>
                        <th data-hide="phone,tablet">Status</th>
                    </tr>
                </thead>

                <tbody>
                    {% for supplier in suppliers %}
                    <tr>
                        <td>{{ supplier.name }}</td>
                        <td>{{ supplier.business }}</td>
                        <td>{{ supplier.address }}</td>
                        <td>{{ supplier.phone }}</td>
                        <td>{{ supplier.email }}</td>
                        <td>
                            <a href="{{ baseUrl }}supplier/view/{{ supplier.id }}" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>

                            {% if supplier.status == constant("Supplier::APPLIED") %}
                            <div class="btn-group">
                                <button class="btn btn-xs btn-warning">
                                    Applied
                                </button>
                                <button class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ baseUrl }}supplier/activate/{{ supplier.id }}">Activate</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="{{ baseUrl }}supplier/reject/{{ supplier.id }}">Reject</a>
                                    </li>
                                </ul>
                            </div>
                            {% endif %}
                            {% if supplier.status == constant("Supplier::ACTIVATED") %}
                                <a class="btn btn-xs btn-info">Activated</a>
                            {% endif %}
                            {% if supplier.status == constant("Supplier::APPROVED") %}
                                <a class="btn btn-xs btn-success">Approved</a>
                            {% endif %}
                        </td>
                    </tr>
                    {% endfor %}
                </tbody>

            </table>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->

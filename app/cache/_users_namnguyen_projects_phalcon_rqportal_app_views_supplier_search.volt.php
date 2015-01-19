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
                    <?php foreach ($suppliers as $supplier) { ?>
                    <tr>
                        <td><?php echo $supplier->name; ?></td>
                        <td><?php echo $supplier->business; ?></td>
                        <td><?php echo $supplier->address; ?></td>
                        <td><?php echo $supplier->phone; ?></td>
                        <td><?php echo $supplier->email; ?></td>
                        <td>
                            <a href="<?php echo $baseUrl; ?>supplier/view/<?php echo $supplier->id; ?>" class="btn btn-xs btn-primary"><i class="fa fa-search"></i> View</a>

                            <?php if ($supplier->status == constant('Supplier::APPLIED')) { ?>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-warning">
                                    Applied
                                </button>
                                <button class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo $baseUrl; ?>supplier/activate/<?php echo $supplier->id; ?>">Activate</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo $baseUrl; ?>supplier/reject/<?php echo $supplier->id; ?>">Reject</a>
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                            <?php if ($supplier->status == constant('Supplier::ACTIVATED')) { ?>
                                <a class="btn btn-xs btn-info">Activated</a>
                            <?php } ?>
                            <?php if ($supplier->status == constant('Supplier::APPROVED')) { ?>
                                <a class="btn btn-xs btn-success">Approved</a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->

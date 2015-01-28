<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-dashboard"></i> Dashboard</h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5> Today Income <span class="txt-color-blue">$47,171</span></h5>
                <div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
                    1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
                </div>
            </li>
            <li class="sparks-info">
                <h5> Weeks Income <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;45%</span></h5>
                <div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
                    110,150,300,130,400,240,220,310,220,300, 270, 210
                </div>
            </li>
            <li class="sparks-info">
                <h5> Months Members <span class="txt-color-greenDark"><i class="fa fa-user"></i>&nbsp;2447</span></h5>
                <div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
                    110,150,300,130,400,240,220,310,220,300, 270, 210
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="row" ng-controller="DashboardCtrl">
    <div class="col-sm-5">
        <!-- new widget -->
        <div class="jarviswidget jarviswidget-color-red" id="wid-id-1" data-widget-editbutton="false" data-widget-colorbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-warning txt-color-white"></i> </span>
                <h2> Alert </h2>
            </header>

            <!-- widget div-->
            <div>
                <div class="widget-body no-padding smart-form">
                    <!-- content goes here -->
                    <?php if ($unpaid_invoice > 0) { ?>
                    <h5 class="todo-group-title"><a href="<?php echo $baseUrl; ?>billing/invoice">Unpaid Invoices</a> (<small class="num-of-tasks"><?php echo $unpaid_invoice; ?></small>)</h5>
                    <?php } ?>

                    <?php if ($outstanding_quote > 0) { ?>
                    <h5 class="todo-group-title"><a href="<?php echo $baseUrl; ?>billing/quote">Outstanding Quotes</a> (<small class="num-of-tasks"><?php echo $outstanding_quote; ?></small>)</h5>
                    <?php } ?>

                    <?php if ($unallocated_quote > 0) { ?>
                    <h5 class="todo-group-title"><a href="<?php echo $baseUrl; ?>quote?q=un-allocated">Un-allocated Quotes</a> (<small class="num-of-tasks"><?php echo $unallocated_quote; ?></small>)</h5>
                    <?php } ?>

                    <?php if ($applied_supplier > 0) { ?>
                    <h5 class="todo-group-title"><a href="<?php echo $baseUrl; ?>supplier">Applied Suppliers</a> (<small class="num-of-tasks"><?php echo $applied_supplier; ?></small>)</h5>
                    <?php } ?>

                    <?php if ($incompleted_supplier > 0) { ?>
                    <h5 class="todo-group-title"><a href="<?php echo $baseUrl; ?>supplier">Incomplete Suppliers</a> (<small class="num-of-tasks"><?php echo $incompleted_supplier; ?></small>)</h5>
                    <?php } ?>

                    <!-- end content -->
                </div>

            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </div>
    <div class="col-sm-7">
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
                  click="onClick">
                </canvas>
            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </div>
</div>

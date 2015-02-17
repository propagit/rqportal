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
</div>

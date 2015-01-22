{% include "layouts/header.volt" %}

{% include "layouts/sidebar.volt" %}


<!-- MAIN PANEL -->
<div id="main" role="main">
    <!-- RIBBON -->
    <div id="ribbon">


        <!-- breadcrumb -->
        {{ elements.getBreadcrumb() }}

    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content" ng-controller="AppCtrl">

        {{ content() }}
        <div id="loading" ng-show="loading > 0"><i class="fa fa-spinner fa-4x fa-spin"></i></div>
    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->






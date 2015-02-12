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
    <div id="content">

        {{ content() }}
    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->






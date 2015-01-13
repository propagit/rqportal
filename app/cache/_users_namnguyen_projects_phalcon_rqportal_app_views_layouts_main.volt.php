<header id="header">
    <div id="logo-group">
        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo2"><img src="<?php echo $baseUrl; ?>img/logo.png" width="110" alt="SmartAdmin"> </span>
        <!-- END LOGO PLACEHOLDER -->

        <!-- Note: The activity badge color changes when clicked and resets the number to 0
        Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
        <span id="activity" class="activity-dropdown"> <i class="fa fa-file-text-o"></i> <b class="badge"> 21 </b> </span>
    </div>

    <!-- projects dropdown -->
    <div class="project-context hidden-xs">

        <span class="label">TODAYS</span>
        <span class="project-selector dropdown-toggle" data-toggle="dropdown">Recent Quotes <i class="fa fa-angle-down"></i></span>

        <!-- Suggestion: populate this list with fetch and push technique -->
        <ul class="dropdown-menu">
            <li>
                <a href="javascript:void(0);">Online e-merchant management system - attaching integration with the iOS</a>
            </li>
            <li>
                <a href="javascript:void(0);">Notes on pipeline upgradee</a>
            </li>
            <li>
                <a href="javascript:void(0);">Assesment Report for merchant account</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="javascript:void(0);"><i class="fa fa-power-off"></i> Clear</a>
            </li>
        </ul>
        <!-- end dropdown-menu-->

    </div>
    <!-- end projects dropdown -->

    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="<?php echo $baseUrl; ?>logout" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->

        <!-- search mobile button (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->

        <!-- input: search field -->
        <form action="search.html" class="header-search pull-right">
            <input id="search-fld"  type="text" name="param" placeholder="Find reports and more">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
            <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
        </form>
        <!-- end input: search field -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->


<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="user-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it -->
            <i class="fa fa-lock"></i> <?php echo $this->elements->getUsername(); ?>
        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive

    To make this navigation dynamic please make sure to link the node
    (the reference to the nav > ul) after page load. Or the navigation
    will not initialize.
    -->
    <nav>
        <?php echo $this->elements->getMenu(); ?>
    </nav>

    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>

</aside>
<!-- END NAVIGATION -->



<!-- MAIN PANEL -->
<div id="main" role="main">
    <!-- RIBBON -->
    <div id="ribbon">


        <!-- breadcrumb -->
        <?php echo $this->elements->getBreadcrumb(); ?>

    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">

        <?php echo $this->getContent(); ?>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->






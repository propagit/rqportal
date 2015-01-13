<?php $this->partial('applicant/header', array('step' => 2)); ?>


<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>What Locations Can you Work In</h3>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-red">
                <li<?php if ($zoneType == 'local') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/location/local#map">Local Zones</a></li>
                <li<?php if ($zoneType == 'country') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/location/country#map">Country Zones</a></li>
                <li<?php if ($zoneType == 'interstate') { ?> class="active"<?php } ?>><a href="<?php echo $baseUrl; ?>/location/interstate#map">Interstate Zones</a></li>
            </ul>

            <!-- Tab panes -->
            <div id="map">
                <?php $this->partial('applicant/' . $zoneType); ?>
            </div>
        </div>
    </div>
</div>

<br />

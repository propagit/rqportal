<div class="container" id="signup-header">
    <div class="row">
        <div class="col-lg-12">
            <div id="logo"></div>
            <h1>Set Up Your Member Account</h1>
            <p>We believe in supporting our customers grow their business by providing them expertise and quality leads. Join Australia’s best and fairest quote company and start winning more work today!</p>
        </div>
    </div>
</div>
<div id="signup-step">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4>Member Account Progress</h4>
                <div id="step-bar">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php if ($step == 1) { ?>10<?php } elseif ($step == 2) { ?>50<?php } else { ?>88<?php } ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php if ($step == 1) { ?>10<?php } elseif ($step == 2) { ?>50<?php } else { ?>88<?php } ?>%"></div>
                    </div>
                    <ul id="steps">
                        <li class="step1"><?php if ($step > 1) { ?><a href="<?php echo $baseUrl; ?>/profile"><?php } ?>
                            <div class="step<?php if ($step >= 1) { ?> completed<?php } ?>">1</div>
                            <label>Company Profile Information</label>
                            <?php if ($step > 1) { ?></a><?php } ?>
                        </li>
                        <li class="step2"><?php if ($step > 2) { ?><a href="<?php echo $baseUrl; ?>/local"><?php } ?>
                            <div class="step<?php if ($step >= 2) { ?> completed<?php } ?>">2</div>
                            <label>Set Work<br />Locations</label>
                            <?php if ($step > 2) { ?></a><?php } ?>
                        </li>
                        <li class="step3">
                            <div class="step<?php if ($step >= 3) { ?> completed<?php } ?>">3</div>
                            <label>Payment<br />Details</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

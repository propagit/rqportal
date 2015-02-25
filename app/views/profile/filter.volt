<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> Quote Filter</h1>

        <a href="{{ baseUrl }}profile/company" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-user"></i></span>My Profile
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/location" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-map-marker"></i></span>Work Locations
        </a> &nbsp;

        <a href="#" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="fa fa-comment-o"></i></span>Quote Filter
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/payment" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-credit-card"></i></span>Payment Info
        </a>
        <br /><br />
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3>What Quotes Do You Want to Receive?</h3>
        <p>Set the quote filter so you only receive the quote that you want</p>
        <br />

        <form method="post" action="{{ baseUrl }}profile/filter" class="form-horizontal">
        <div class="form-group">
            <label for="minbed" class="col-lg-2">Bedrooms</label>
            <div class="col-lg-2">
                <?php echo $this->tag->selectStatic(array(
                    'bedrooms', Removal::listBedsOptions(),
                    'class' => 'form-control'
                )); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button class="btn btn-red" type="submit">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

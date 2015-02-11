<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> Company Profile</h1>

        <a href="{{ baseUrl }}profile/company" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-user"></i></span>My Profile
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/location" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-map-marker"></i></span>Work Locations
        </a> &nbsp;

        <a href="#" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="fa fa-credit-card"></i></span>Payment Info
        </a>
        <br /><br />
    </div>
</div>

<div class="row">
    <div class="col-lg-12">

        <h3>Your Payment Details</h3>
        <p>
            Removalist Quotes will only charge you for legitimate leads that you receive.<br />
            A lead to a job in your zone is charged at a flat rate. We will auto bill your credit card once your account balance reaches $500.<br />
            To read our payment policy, <a ng-click="policy()" class="text">click here</a>.
        </p>
        <br />

        <form action="{{ baseUrl }}profile/payment" method="post" name="paymentForm" class="form-horizontal" novalidate>

        <div class="form-group">
            <label for="title" class="col-lg-2">Title</label>

            <div class="col-lg-2">
                {{ elements.ewayTitle('title', eway_customer ? eway_customer.CustomerTitle : '') }}
            </div>
        </div>

        <div class="form-group">
            <label for="firstname" class="col-lg-2">Card Name</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name" value="{{ eway_customer ? eway_customer.CustomerFirstName : '' }}" required>
            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="lastname" placeholder="Last name" value="{{ eway_customer ? eway_customer.CustomerLastName : '' }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="cardnumber" class="col-lg-2">Card Number</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" name="ccnumber" id="cardnumber" value="{{ eway_customer ? eway_customer.CCNumber : '' }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="ccexpmonth" class="col-lg-2">Expiry</label>
            <div class="col-lg-2">
                {{ elements.cardMonth('ccexpmonth', eway_customer ? eway_customer.CCExpiryMonth : '') }}
            </div>
            <div class="col-lg-2">
                {{ elements.cardYear('ccexpyear', eway_customer ? eway_customer.CCExpiryYear : '') }}
            </div>
        </div>
        <div class="form-group">
            <label for="cvv" class="col-lg-2">CVV Number</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" name="cvn" id="cvv" value="{{ supplier.cvn }}" required>
            </div>
        </div>


        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                {{ flash.output() }}
                <button class="btn btn-red" type="submit">Update</button>
            </div>
        </div>

        </form>

    </div>
</div>

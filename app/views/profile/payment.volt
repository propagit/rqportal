<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> Company Profile</h1>

        <a href="#" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="fa fa-user"></i></span>My Profile
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/location" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-map-marker"></i></span>Work Locations
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/payment" class="btn btn-labeled btn-default">
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

        <form name="paymentForm" class="form-horizontal" novalidate>

        <div class="form-group">
            <label for="cardname" class="col-lg-2">Card Name</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" ng-model="card.name" id="cardname" required>
            </div>
        </div>

        <div class="form-group">
            <label for="cardnumber" class="col-lg-2">Card Number</label>
            <div class="col-lg-4">
                <input type="text" class="form-control" ng-model="card.number" id="cardnumber" required>
            </div>
        </div>

        <div class="form-group">
            <label for="expiry" class="col-lg-2">Expiry</label>
            <div class="col-lg-2">
                <select class="form-control" ng-model="card.month" name="month" id="expiry" data-card-expiration required>
                    <option disabled selected value="">Month</option>
                    <option ng-repeat="month in months" value="[[$index+1]]" > [[$index+1]] - [[month]]</option>
                </select>
            </div>
            <div class="col-lg-2">
                <select class="form-control" ng-model="card.year" name="year" required>
                    <option disabled selected value="">Year</option>
                    <option ng-repeat="year in [] | range:currentYear:currentYear+13">[[year]]</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="cvv" class="col-lg-2">CVV Number</label>
            <div class="col-lg-2">
                <input type="text" class="form-control" ng-model="card.cvv" id="cvv" required>
            </div>
        </div>


        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button class="btn btn-red" ng-disabled="paymentForm.$invalid" ng-click="complete()">Update</button>
            </div>
        </div>

        </form>

    </div>
</div>

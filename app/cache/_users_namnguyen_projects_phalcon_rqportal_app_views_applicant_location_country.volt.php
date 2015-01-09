<div class="row">
    <div class="col-lg-7">
        <h3>Create Your Country Zone</h3>
        <p>You will receive work in this zone provided a post code from your local zone is either in the pick up or drop off address.</p>

        <div class="map" ng-controller="CountryMapCtrl">
            <ui-gmap-google-map center='map.center' zoom='map.zoom'></ui-gmap-google-map>
        </div>

    </div>
    <div class="col-lg-5">
        <h4>Add Your Local Zones</h4>
        <p>Choose a post code from your local zone then choose the distance from the post code you can work in.</p>
        <form class="form-block">
            <div>
                <label for="postcode">Choose your local zone post code</label>
                <input type="number" name="postcode" id="postcode" />
            </div>
            <div>
                <label for="distance">Select a distance you can work from this post code</label>
                <input type="number" name="distance" id="distance" />
            </div>
            <div>
                <button class="btn btn-red">Add Zone</button>
            </div>
        </form>
        <hr />

        <h4>YOUR LOCAL ZONES</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Postcode</td>
                    <td>Radius</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>3000</td>
                    <td>40 km</td>
                    <td><i class="fa fa-pencil-square-o"></i></td>
                    <td><i class="fa fa-times"></i></td>
                </tr>
            </tbody>
        </table>
        <br />
        <button class="btn btn-red">Next Step</button>
    </div>
</div>

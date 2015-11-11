<div ng-init="query = '{{ query }}'">
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark fw300"><i class="fa-fw fa fa-user"></i> Supplier
            <span>>
                Search Suppliers
            </span>
        </h1>
    </div>
</div>

<div ng-controller="SupplierCtrl">

    <!-- Widget ID (each widget will need unique ID)-->
    <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-table"></i> </span>
            <h2>Suppliers </h2>

        </header>

        <!-- widget div-->
        <div>

            <!-- widget content -->
            <div class="widget-body no-padding">            

                <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th class="hasinput" colspan="5">
                                <input type="text" ng-model="keyword" class="form-control" placeholder="Keyword" />
                            </th>
                            <th colspan="3">
                                <?php echo $this->tag->selectStatic(array(
                                    'status', Supplier::getStatus(),
                                    'useEmpty'  => true,
                                    'emptyText' => 'Any',
                                    'class' => 'form-control',
                                    'ng-model' => 'filter_status'
                                )); ?>
                            </th>
                        </tr>
                        <tr>
                            <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
                            <th>Business</th>
                            <th data-hide="phone,tablet">Note</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-map-marker txt-color-blue hidden-md hidden-sm hidden-xs"></i>Address</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i>Phone</th>
                            <th data-hide="phone,tablet">Email</th>
                            <th data-hide="phone,tablet">Status</th>
                            <th data-hide="phone,tablet">Free</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="supplier in suppliers | filter: keyword | filter: filterSupplier">
                            <td>[[ supplier.name ]]</td>
                            <td>[[ supplier.business ]]</td>
                            <td>
                            <!-- <a ns-popover ns-popover-template="popover" ns-popover-trigger="click" ns-popover-placement="bottom" ng-click="setSupplierId(supplier.id)">
                            <i class="fa fa-pencil"></i></a> -->

                            &nbsp; 
                            <span ng-show="supplier.note != null && supplier.note != ''"><a ns-popover ns-popover-template="loadnote" ns-popover-trigger="click" ns-popover-placement="bottom" ng-click="getSupplierNote(supplier.id)" style="color: #a90329;"><i class="fa fa-comment"></i></a></span>

                            <span ng-show="supplier.note == null || supplier.note == ''">
                            <a ns-popover ns-popover-template="loadnote" ns-popover-trigger="click" ns-popover-placement="bottom" ng-click="getSupplierNote(supplier.id)"><i class="fa fa-comment-o"></i></a>
                            </span>

                            </td>
                            <td>[[ supplier.address ]]</td>
                            <td>[[ supplier.phone ]]</td>
                            <td>[[ supplier.email ]]</td>
                            <td>
                                <span ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}" class="text-warning">Applied</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::ACTIVATED") }}" class="text-primary">Activated</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::APPROVED") }}" class="text-success">Approved</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::INACTIVED") }}" class="text-muted">In-actived</span>

                                <span ng-if="supplier.status == {{ constant("Supplier::REJECTED") }}" class="text-danger">Rejected</span>
                            </td>
                            <td class="text-center">
                                <label>
                                  <input ng-if="supplier.free" type="checkbox" class="checkbox" checked>
                                  <input ng-if="!supplier.free" type="checkbox" class="checkbox">
                                  <span ng-click="setFree([[ supplier.id ]])"></span>
                                </label>
                            </td>
                            <td align="left">
                                <a ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}"  href="{{ baseUrl }}supplier/activate/[[ supplier.id ]]" class="btn btn-xs btn-warning">Activate</a>

                                <a ng-if="supplier.status == {{ constant("Supplier::APPLIED") }}" ng-click="reject([[ supplier.id ]])" class="btn btn-xs btn-danger">Reject</a>

                                <a ng-click="reactivate([[ supplier.id ]])" ng-if="supplier.status == {{ constant("Supplier::INACTIVED") }}" class="btn btn-xs btn-info">Re-activate</a>


                                <a ng-if="supplier.status == {{ constant("Supplier::ACTIVATED") }} && supplier.activation_key" href="{{ baseUrl }}applicant/register/[[ supplier.id ]]/[[ supplier.activation_key ]]" class="btn btn-xs btn-primary">Complete Profile</a>

                                <a ng-if="supplier.status == {{ constant("Supplier::ACTIVATED") }} && !supplier.activation_key" href="{{ baseUrl }}supplier/login/[[ supplier.user_id ]]" class="btn btn-xs btn-primary">Complete Profile</a>

                                <a ng-if="supplier.status == {{ constant("Supplier::APPROVED") }}" href="{{ baseUrl }}supplier/login/[[ supplier.user_id ]]" class="btn btn-xs btn-success">Login as supplier</a>
                                &nbsp;

                                <div class="btn-group" ng-if="supplier.status >= {{ constant("Supplier::ACTIVATED") }}">
                                    <button class="btn btn-danger btn-xs dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-right">
                                        <li ng-if="supplier.status > {{ constant("Supplier::ACTIVATED") }}">
                                            <a ng-click="deactivate([[ supplier.id ]])">De-activate</a>
                                        </li>
                                        <li>
                                            <a ng-click="delete([[ supplier.id ]])">Delete this supplier</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <!-- end widget content -->

        </div>
        <!-- end widget div -->

    </div>
    <!-- end widget -->
</div>


</div>


<script type="text/ng-template" id="popover">
  <div class="triangle note-dialog">
      <div class="page-title"><span>Add A Note</span> </div>
      <div ng-show="noteForm">
          <textarea ng-model="note" placeholder="Enter new note"></textarea>
          <div class="button-group">
            <button type="submit" class="btn btn-danger" ng-click="saveNote(note)">
            Add The Note</button>
          </div>
      </div>
      <div ng-hide="noteResponse">
        <p>Your note is added successfully to this supplier.</p>
      </div>
  </div>
</script>

<script type="text/ng-template" id="loadnote">
  <div class="triangle note-dialog">
      <div class="page-title"><span>Supplier Note</span> </div>
      <div ng-show="noteForm">
          <textarea ng-model="note" placeholder="Enter new note"></textarea>
          <div class="button-group">
            <button type="submit" class="btn btn-danger" ng-click="saveNote(note)">
            Update Note</button>
          </div>
      </div>
      <div ng-hide="noteResponse">
        <p>Your note is added successfully to this supplier.</p>
      </div>
  </div>
</script>


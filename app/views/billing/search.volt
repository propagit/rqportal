<form class="form-horizontal">
    <div class="form-group">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <angucomplete-alt id="pickup"
                  minlength="1"
                  placeholder="Enter supplier name, company, business to search..."
                  pause="400"
                  selected-object="params.supplier"
                  remote-url="{{ baseUrl }}supplierajax/search/"
                  remote-url-data-field="suppliers"
                  title-field="name"
                  description-field="business"
                  input-class="form-control"
                  match-class="highlight"
                  field-required="true" disable-input="params.allocated == 'not_allocated'"></angucomplete-alt>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3">
            <div class="input-group">
                <ng-bs3-datepicker data-ng-model='params.from_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="From Date" required></ng-bs3-datepicker>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <ng-bs3-datepicker data-ng-model='params.to_date' language="en-ca" date-format="YYYY-MM-DD" placeholder="To Date" required></ng-bs3-datepicker>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3">
            <?php echo $this->tag->selectStatic(array(
                'status', Invoice::getStatus(),
                'class' => 'form-control',
                'ng-model' => 'params.status'
            )); ?>
        </div>
    </div>
</form>

<div class="row">
    <section class="col-lg-6">
        <a class="btn btn-labeled btn-danger" ng-click="searchInvoices(params)">
            <span class="btn-label"><i class="fa fa-search"></i></span>Search
        </a>
    </section>
</div>

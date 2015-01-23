<header>
    <h2>System Configuration</h2>

</header>

<!-- widget div-->
<div>

    <!-- widget content -->
    <div class="widget-body no-padding">

        <form action="{{ baseUrl }}setting" method="post" class="smart-form no-widget">
            <fieldset>
                <section>{{ flash.output() }}</section>
                <section>
                    <label class="checkbox">
                        <input type="checkbox" name="auto_allocate_quote" value="1" {{ auto_allocate_quote.value ? 'checked' : '' }}>
                        <i></i>Auto allocate quotes</label>
                </section>

                <div class="row">
                    <section class="col">
                        <label class="label">Suppliers Per Quote</label>
                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                            <input type="text" name="supplier_per_quote" value="{{ supplier_per_quote.value }} ">
                        </label>
                    </section>
                </div>

                <div class="row">
                    <section class="col">
                        <label class="label">Price Per Quote</label>
                        <label class="input"> <i class="icon-prepend fa fa-dollar"></i>
                            <input type="text" name="price_per_quote" value="{{ price_per_quote.value }}">
                        </label>
                    </section>
                </div>

                <div class="row">
                    <section class="col">
                        <label class="label">Auto Invoice Threshold</label>
                        <label class="input"> <i class="icon-prepend fa fa-dollar"></i>
                            <input type="text" name="invoice_threshold" value="{{ invoice_threshold.value }}">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col">
                        <div class="note">Invoice is auto-generated once the supplier account balance reach this amount</div>
                    </section>
                </div>

            </fieldset>

            <footer>
                <button type="submit" name="submit" class="btn btn-danger">
                    Update
                </button>
            </footer>
        </form>

    </div>
</div>

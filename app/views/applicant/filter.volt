{% include "applicant/header" with ['step': 3] %}

{{ content() }}

<div class="container">
	<?php if(0){ ?>
    <div class="row">
        <div class="col-lg-12">
            <h3>What Quotes Do You Want to Receive?</h3>
            <p>Set the quote filter so you only receive the quotes that you want</p>
            <br />

            <form method="post" action="{{ baseUrl }}applicant/filter" class="form-horizontal">
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
                    <button class="btn btn-labeled btn-danger" type="submit">
                        <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>Next Step
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <?php } ?>
    
    
    <div class="row">
        <div class="col-lg-12">
            <h3>Do you want to receive International Quotes</h3>
       		<p>Set the quote filter so you only receive the quotes that you want</p>
            <br />

            <form method="post" action="{{ baseUrl }}applicant/filter" class="form-horizontal">
            <div class="form-group">
                <label for="minbed" class="col-lg-3">Receive International Quotes</label>
                <div class="col-lg-2">
                   <?php echo $this->tag->selectStatic(array(
                    'interlational_quotes', Removal::listInternationalOptions(),
                    'class' => 'form-control'
                )); ?>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-10">
                    <button class="btn btn-labeled btn-danger" type="submit">
                        <span class="btn-label"><i class="glyphicon glyphicon-chevron-right"></i></span>Next Step
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

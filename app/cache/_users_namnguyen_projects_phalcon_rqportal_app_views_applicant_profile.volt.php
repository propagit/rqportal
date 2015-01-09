<?php $this->partial('applicant/header', array('step' => 1)); ?>

<?php echo $this->getContent(); ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>Your Company Profile Information</h3>
            <p>Enter details about your company</p>
            <br />

            <?php echo $this->tag->form(array('applicant/profile', 'role' => 'form', 'class' => 'form-horizontal')); ?>

            <?php foreach ($form as $element) { ?>
                <?php if (is_a($element, 'Phalcon\Forms\Element\Hidden')) { ?>
                <?php echo $element; ?>
                <?php } else { ?>
                <div class="form-group">
                    <?php echo $element->label(array('class' => 'col-lg-2')); ?>
                    <div class="col-lg-10">
                        <?php echo $element->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
                <?php } ?>
            <?php } ?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-danger" type="submit">Next Step</button>
                </div>
            </div>

            </form>

        </div>
    </div>
</div>

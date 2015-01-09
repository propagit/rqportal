
<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <br />
            <?php echo $this->flash->output(); ?>
            <?php echo $this->tag->form(array('signup', 'role' => 'form', 'class' => 'form-horizontal')); ?>

            <?php foreach ($form as $element) { ?>
                <div class="form-group">
                    <?php echo $element->label(array('class' => 'col-lg-2')); ?>
                    <div class="col-lg-10">
                        <?php echo $element->render(array('class' => 'form-control')); ?>
                    </div>
                </div>
            <?php } ?>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-red" type="submit">Sign up</button>
                </div>
            </div>

            </form>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html <?php if ($this->router->getControllerName() != 'applicant') { ?> class="be"<?php } ?>>
    <head>
        <meta charset="UTF-8" />
        <?php echo $this->tag->getTitle(); ?>
        <?php echo $this->tag->stylesheetLink('bootstrap/css/bootstrap.min.css'); ?>
        <?php echo $this->tag->stylesheetLink('smart-admin/css/smartadmin-production.min.css'); ?>
        <?php echo $this->tag->stylesheetLink('js/lib/angular-bootstrap3-datepicker/ng-bs3-datepicker.css'); ?>
        <?php echo $this->tag->stylesheetLink('js/lib/angucomplete/angucomplete-alt.css'); ?>
        <?php echo $this->tag->stylesheetLink('css/app.min.css'); ?>

        <?php echo $this->tag->javascriptInclude('js/lib/jquery.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('bootstrap/js/bootstrap.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('smart-admin/js/app.config.js'); ?>
        <?php echo $this->tag->javascriptInclude('smart-admin/js/app.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/angular.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/ui-bootstrap-tpls-0.12.0.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/masks.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/lodash.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/angular-google-maps.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/moment-with-locales.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/angular-bootstrap3-datepicker/ng-bs3-datepicker.min.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/lib/angucomplete/angucomplete-alt.min.js'); ?>

        <?php echo $this->tag->javascriptInclude('js/app.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/controllers/applicant.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/controllers/quote.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/controllers/billing.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/config.js'); ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Removalist Quote Portal">
        <meta name="author" content="Propagate Team">
    </head>
    <body ng-app="rqportal">
        <?php echo $this->getContent(); ?>


        <script>
        $(function () {
            pageSetUp();
        })
        </script>
    </body>
</html>

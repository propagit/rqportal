<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <?php echo $this->tag->getTitle(); ?>
        <?php echo $this->tag->stylesheetLink('bootstrap/css/bootstrap.min.css'); ?>
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
        <?php echo $this->tag->javascriptInclude('js/app.js'); ?>
        <?php echo $this->tag->javascriptInclude('js/config.js'); ?>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Removalist Quote Portal">
        <meta name="author" content="Propagate Team">
    </head>
    <body ng-app="rqportal">
        <?php echo $this->getContent(); ?>

        <script type="text/ng-template" id="loading">
            <div class="modal-body">
                <br />
                <p align="center"><i class="fa fa-cog fa-3x fa-spin"></i></p>
                <h3 class="modal-title" align="center">Please wait ...</h3>
                <br />
            </div>
        </script>
    </body>
</html>

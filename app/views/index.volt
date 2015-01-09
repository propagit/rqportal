<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.min.css') }}
        {{ stylesheet_link('css/app.min.css') }}
        {{ javascript_include('js/lib/jquery.min.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.min.js') }}
        {{ javascript_include('smart-admin/js/app.config.js') }}
        {{ javascript_include('smart-admin/js/app.min.js') }}
        {{ javascript_include('js/lib/angular.min.js') }}
        {{ javascript_include('js/lib/ui-bootstrap-tpls-0.12.0.min.js') }}
        {{ javascript_include('js/lib/masks.min.js') }}
        {{ javascript_include('js/lib/lodash.min.js')}}
        {{ javascript_include('js/lib/angular-google-maps.min.js') }}
        {{ javascript_include('js/app.js') }}
        {{ javascript_include('js/config.js') }}

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Removalist Quote Portal">
        <meta name="author" content="Propagate Team">
    </head>
    <body ng-app="rqportal">
        {{ content() }}

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

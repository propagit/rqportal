<!DOCTYPE html>
<html {% if router.getControllerName() != "applicant" %} class="be"{% endif %}>
    <head>
        <meta charset="UTF-8" />
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.min.css') }}
        {{ stylesheet_link('smart-admin/css/smartadmin-production.min.css') }}
        {{ stylesheet_link('js/lib/angular-bootstrap3-datepicker/ng-bs3-datepicker.css') }}
        {{ stylesheet_link('js/lib/angucomplete/angucomplete-alt.css') }}
        {{ stylesheet_link('js/lib/angular-chart/angular-chart.css') }}
        {{ stylesheet_link('css/app.min.css') }}



        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Removalist Quote Portal">
        <meta name="author" content="Propagate Team">
    </head>
    <body ng-app="rqportal">
        {{ content() }}


        {{ javascript_include('js/lib/jquery.min.js') }}

        <!-- SPARKLINES -->
        {{ javascript_include('js/lib/sparkline/jquery.sparkline.min.js') }}

        {{ javascript_include('bootstrap/js/bootstrap.min.js') }}
        {{ javascript_include('smart-admin/js/app.config.js') }}
        {{ javascript_include('smart-admin/js/app.min.js') }}
        {{ javascript_include('js/lib/angular.min.js') }}

        <!-- Chart -->
        {{ javascript_include('js/lib/chart/Chart.min.js') }}
        {{ javascript_include('js/lib/angular-chart/angular-chart.js') }}

        {{ javascript_include('js/lib/ui-bootstrap-tpls-0.12.0.min.js') }}
        {{ javascript_include('js/lib/masks.min.js') }}
        {{ javascript_include('js/lib/lodash.min.js')}}
        {{ javascript_include('js/lib/angular-google-maps.min.js') }}
        {{ javascript_include('js/lib/moment-with-locales.min.js') }}
        {{ javascript_include('js/lib/angular-bootstrap3-datepicker/ng-bs3-datepicker.min.js') }}
        {{ javascript_include('js/lib/angucomplete/angucomplete-alt.min.js') }}

        {{ javascript_include('js/app.js') }}
        {{ javascript_include('js/controllers/dashboard.js') }}
        {{ javascript_include('js/controllers/applicant.js') }}
        {{ javascript_include('js/controllers/supplier.js') }}
        {{ javascript_include('js/controllers/quote.js') }}
        {{ javascript_include('js/controllers/billing.js') }}
        {{ javascript_include('js/config.js') }}
        <script>
        $(function () {
            pageSetUp();
        })
        </script>
    </body>
</html>

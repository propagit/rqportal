
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-user"></i> Company Profile</h1>

        <a href="#" class="btn btn-labeled btn-danger">
            <span class="btn-label"><i class="fa fa-user"></i></span>My Profile
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/password" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-lock"></i></span>Password
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/location" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-map-marker"></i></span>Work Locations
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/filter" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-comment-o"></i></span>Quote Filter
        </a> &nbsp;

        <a href="{{ baseUrl }}profile/payment" class="btn btn-labeled btn-default">
            <span class="btn-label"><i class="fa fa-credit-card"></i></span>Payment Info
        </a>
        <br /><br />
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h3>Your Company Profile Information</h3>
        <p>Enter details about your company</p>
        <br />

        {{ flash.output() }}

        {{ form("profile/company", 'role': 'form', 'class': 'form-horizontal')}}
        {% for element in form %}
            {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
            {{ element }}
            {% else %}
            <div class="form-group">
                {{ element.label(['class': 'col-lg-2']) }}
                <div class="col-lg-10">
                    {{ element.render(['class': 'form-control']) }}
                    {% if element.getName() == 'email_quote_cc' %}
                    <span id="helpBlock" class="help-block">You can include as many emails as you want. Use comma (,) to separate the email addresses.</span>
                    {% endif %}
                </div>
            </div>
            {% endif %}
        {% endfor %}

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button class="btn btn-danger" type="submit">Save <i class="fa fa-check"></i></button>
            </div>
        </div>
        </form>
    </div>
</div>

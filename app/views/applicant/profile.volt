{% include "applicant/header" with ['step': 1] %}

{{ content() }}

<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <h3>Your Company Profile Information</h3>
            <p>Enter details about your company</p>
            <br />

            {{ form("applicant/profile", 'role': 'form', 'class': 'form-horizontal')}}

            {% for element in form %}
                {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
                {{ element }}
                {% else %}
                <div class="form-group">
                    {{ element.label(['class': 'col-lg-2']) }}
                    <div class="col-lg-10">
                        {{ element.render(['class': 'form-control']) }}
                    </div>
                </div>
                {% endif %}
            {% endfor %}

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-red" type="submit">Next Step</button>
                </div>
            </div>

            </form>

        </div>
    </div>
</div>

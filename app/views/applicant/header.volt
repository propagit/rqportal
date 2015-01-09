<div class="container" id="signup-header">
    <div class="row">
        <div class="col-lg-12">
            <div id="logo"></div>
            <h1>Set Up Your Member Account</h1>
            <p>We believe in supporting our customers grow their business by providing them expertise and quality leads. Join Australia’s best and fairest quote company and start winning more work today!</p>
        </div>
    </div>
</div>
<div id="signup-step">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h4>Member Account Progress</h4>
                <div id="step-bar">
                    <div class="progress">
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{% if step == 1 %}10{% elseif step == 2 %}50{% else %}88{% endif %}" aria-valuemin="0" aria-valuemax="100" style="width: {% if step == 1 %}10{% elseif step == 2 %}50{% else %}88{% endif %}%"></div>
                    </div>
                    <ul id="steps">
                        <li class="step1">{% if step >1 %}<a href="{{ baseUrl }}/profile">{% endif %}
                            <div class="step{% if step >= 1 %} completed{% endif %}{% if step == 1 %} current{% endif %}">1</div>
                            <label>Company Profile Information</label>
                            {% if step >1 %}</a>{% endif %}
                        </li>
                        <li class="step2">{% if step > 2 %}<a href="{{ baseUrl }}/local">{% endif %}
                            <div class="step{% if step >= 2 %} completed{% endif %}{% if step == 2 %} current{% endif %}">2</div>
                            <label>Set Work<br />Locations</label>
                            {% if step > 2 %}</a>{% endif %}
                        </li>
                        <li class="step3">
                            <div class="step{% if step >= 3 %} completed{% endif %}{% if step == 3 %} current{% endif %}">3</div>
                            <label>Payment<br />Details</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

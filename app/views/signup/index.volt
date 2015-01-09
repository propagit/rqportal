
<div class="container">
    <div class="row">
        <div class="col-lg-12">

            <br />
            {{ flash.output() }}
            {{ form("signup", 'role': 'form', 'class': 'form-horizontal')}}

            {% for element in form %}
                <div class="form-group">
                    {{ element.label(['class': 'col-lg-2']) }}
                    <div class="col-lg-10">
                        {{ element.render(['class': 'form-control']) }}
                    </div>
                </div>
            {% endfor %}

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button class="btn btn-red" type="submit">Sign up</button>
                </div>
            </div>

            </form>
        </div>
    </div>
</div>

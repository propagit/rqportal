
{{ content() }}

<div class="container" id="login-page" role="main">
    {{ form('reset', 'id': 'login-form', 'class': 'lockscreen animated flipInY smart-form client-form') }}
        <header>Reset Your Password</header>

        <fieldset>
            {{ flash.output() }}
            <section>
                <label class="label">Username</label>
                <div class="login-input">
                    <label class="input">
                        <i class="icon-append fa fa-user"></i>
                        <input type="text" name="username">
                        <b class="tooltip tooltip-top-right">
                            <i class="fa fa-user txt-color-teal"></i>
                            Please enter your username
                        </b>
                    </label>
                </div>
            </section>
        </fieldset>
        <!--{% if user %}{{ user.reset_key }} {% endif %}-->

        <footer>
            <p class="pull-left"><br /><a href="{{ baseUrl }}login">Login</a></p>
            <button type="submit" class="btn btn-red" id="btn-login">Reset</button>
        </footer>
    </form>
</div>


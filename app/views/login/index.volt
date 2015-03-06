
{{ content() }}

<div class="container" id="login-page" role="main">
    {{ form('login', 'id': 'login-form', 'class': 'lockscreen animated flipInY smart-form client-form') }}
        <header>Sign In</header>

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

            <section>
                <label class="label">Password</label>
                <div class="login-input">
                    <label class="input">
                        <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="password">
                        <b class="tooltip tooltip-top-right">
                            <i class="fa fa-lock txt-color-teal"></i>
                            Enter your password
                        </b>
                    </label>
                </div>
            </section>
        </fieldset>

        <footer>
            <p class="pull-left"><br /><a href="{{ baseUrl }}reset">Forgot your password?</a></p>
            <button type="submit" class="btn btn-red" id="btn-login">Sign in</button>
        </footer>
    </form>
</div>


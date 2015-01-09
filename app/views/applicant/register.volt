
{{ content() }}
<div class="container" id="signup-header">
    <div class="row">
        <div class="col-lg-12">
            <div id="logo"></div>
            <h1>Welcome To Removalist Quote</h1>
            <p>
                You are joining Australias leading removals quote company<br />
                and on the way to securing your business a steady flow of work.
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div  id="signup-account">
                <h3>Create Your Account Login Details</h3>
                <p>
                    Set up your username and password for managing your account.<br />
                    <a href="#" class="text"><i class="fa fa-bookmark"></i> Bookmark</a> this page to return to your member account at any time.
                </p>
                <br />
                {{ form('applicant/register/' ~ supplier.id ~ '/' ~ supplier.activation_key, 'id': 'registerForm', 'class': 'form-horizontal', 'onbeforesubmit': 'return false') }}

                <div class="form-group">
                    {{ form.label('username', ['class': ' col-lg-2']) }}
                    <div class="col-lg-6">
                        {{ form.render('username', ['class': 'form-control', 'placeholder': 'Username must be six or more characters long']) }}
                    </div>
                </div>


                <div class="form-group">
                    {{ form.label('password', ['class': 'col-lg-2']) }}
                    <div class="col-lg-6">
                        {{ form.render('password', ['class': 'form-control', 'placeholder': 'Password must contain at least 1 capital letter and 1 number']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2" for="repeatPassword">Repeat Password</label>
                    <div class="col-lg-6">
                        {{ password_field('repeatPassword', 'class': 'form-control', 'placeholder': 'Confirm Your Password') }}
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-6">
                        {{ submit_button('Continue', 'class': 'btn btn-red', 'onclick': 'return SignUp.validate();') }}
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

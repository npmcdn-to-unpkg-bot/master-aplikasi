@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')
<script language="javascript">
function login()
{
	var strError = "";
	var email = $('#email').val();
	var password = $('#password').val();
	var remember = $('#remember').val();
	var _token = $('#_token').val();
	
	if(email=="") strError += "<li>Email harus diisi</li>";
	if(password=="") strError += "<li>Password harus diisi</li>";
	
	if(strError=="")
	{
			$.post("/auth/login", { 
			email: email,
			password: password,
			remember: remember,
			_token:_token,
			submit: "Login"
			} )
			.done(function( data ) {
				if(data=="success")
				{
					$("#result").empty().append("<div class=\"alert alert-success\">Login Success</div>").hide().fadeIn();
					window.setTimeout(function () {
						<?php
						if(Request::server('HTTP_REFERER')=="")
						{
							$prev = "/auth/dashboard";	
						}
						else
						{
							$prev = 	Request::server('HTTP_REFERER');
						}
						?>
        				location.href = "{{ $prev }}";
    				}, 1000);
				}
				else
				{
					$("#result").empty().append(data).hide().fadeIn();
					$('#email').val('');
					$('#password').val('');
				}
			});
	
	}
	else
	{
			$("#result").empty().append("<div class=\"alert alert-danger\">"+ strError +"</div>").hide().fadeIn();
	}
		
		
	return false;
}

</script>

                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    
                    <div class="panel-body">
                    	<div id="result"></div>
                        <form role="form" method="POST" action="/auth/login"  onSubmit="return login()">
                            <fieldset>
                            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" id="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                                
                            </fieldset>
                        </form>
                        <br />
                        <a href="/auth/password/email">Forgot password?</a> or <a href="/auth/register">Register</a>
                    </div>
                
@endsection
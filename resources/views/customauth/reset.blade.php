@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')
<script language="javascript">
function reset_password()
{
	var strError = "";
	var email = $('#email').val();
	var password = $('#password').val();
	var password_confirmation = $('#password_confirmation').val();
	var token = $('#token').val();
	var _token = $('#_token').val();
	
	
	if(email=="") strError += "<li>Email harus diisi</li>";
	if(password=="") strError += "<li>Password harus diisi</li>";
	if(password_confirmation=="") strError += "<li>Password confirmation harus diisi</li>";
	
	
	if(strError=="")
	{
		if(password!=password_confirmation)
		{
			$("#result").empty().append("<div class=\"alert alert-danger\"><li>Password dan Confirm password tidak sama</li></div>").hide().fadeIn();
		}
		else
		{
			$.post("/auth/password/reset", { 
			name: name, 
			email: email,
			password: password,
			password_confirmation: password_confirmation,
			_token:_token,
			token:token,
			submit: "Reset"
			} )
			.done(function( data ) {
				if(data=="success")
				{
					$("#result").empty().append("<div class=\"alert alert-success\">Password changed</div>").hide().fadeIn();
					window.setTimeout(function () {
        				location.href = "/user/dashboard";
    				}, 1000);
				}
				else
				{
					$("#result").empty().append(data).hide().fadeIn();
				}
			});
		}
	
	}
	else
	{
			$("#result").empty().append("<div class=\"alert alert-danger\">"+ strError +"</div>").hide().fadeIn();
	}
		
		
	return false;
}

</script>
                    <div class="panel-heading">
                        <h3 class="panel-title">Reset Password</h3>
                    </div>
                    
                    <div class="panel-body">
                    	<div id="result"></div>
                        <form role="form" method="POST" action="/auth/password/reset" onSubmit="return reset_password()">
                            <fieldset>
                            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
                                <input type="hidden" name="token" id="token" value="{{ $token }}" />
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Reset Password</button>
                                
                            </fieldset>
                        </form>
                    </div>
                
@endsection
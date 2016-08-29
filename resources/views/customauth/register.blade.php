@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')
<script language="javascript">
function register()
{
	var strError = "";
	var name = $('#name').val();
	var email = $('#email').val();
	var password = $('#password').val();
	var password_confirmation = $('#password_confirmation').val();
	var _token = $('#_token').val();
	
	if(name=="") strError += "<li>Nama harus diisi</li>";
	if(email=="") strError += "<li>Email harus diisi</li>";
	if(password=="") strError += "<li>Password harus diisi</li>";
	if(password_confirmation=="") strError += "<li>Password confirmation harus diisi</li>";
	
	if(strError=="")
	{
		if(password!=password_confirmation)
		{
			$("#result").empty().append("<div class=\"alert alert-danger\"><li>New password dan Confirm new password tidak sama</li></div>").hide().fadeIn();
		}
		else
		{
			$.post("/auth/register", {
			name: name, 
			email: email,
			password: password,
			password_confirmation: password_confirmation,
			_token:_token,
			submit: "Login"
			} )
			.done(function( data ) {
					$("#result").empty().append(data).hide().fadeIn();
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
                        <h3 class="panel-title">Register</h3>
                    </div>
                    
                    <div class="panel-body">
                    	<div id="result"></div>
                        <form role="form"  method="POST" action="/auth/register" onSubmit="return register()">
                            <fieldset>
                            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
                                <div class="form-group">
                                    <input class="form-control" id="name" placeholder="Name" name="name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" type="password" value="">
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Register</button>
                            </fieldset>
                        </form>
                    </div>
                
@endsection
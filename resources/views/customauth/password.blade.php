@extends('sbadminv2.login')
@section('title', 'Page Title')
@section('content')
<script language="javascript">
function reset_password()
{
	var strError = "";
	var email = $('#email').val();
	var _token = $('#_token').val();
	
	if(email=="") strError += "<li>Email harus diisi</li>";
	
	
	if(strError=="")
	{
			$.post("/auth/password/email", { 
			email: email,
			_token:_token,
			submit: "Reset"
			} )
			.done(function( data ) {
					$("#result").empty().append(data).hide().fadeIn();
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
                        <h3 class="panel-title">Forgot Password</h3>
                    </div>
                    
                    <div class="panel-body">
                    <div id="result"></div>
                    	<form role="form" method="POST" action="/auth/password/email" onSubmit="return reset_password()">
                            <fieldset>
                            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autofocus>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type="submit" class="btn btn-lg btn-success btn-block">Send Password Reset Link</button>
                            </fieldset>
                        </form>
                    </div>
                
@endsection
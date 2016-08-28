@extends('sbadminv2.backend')
@section('title', 'Setting')
@section('user', $user->name )
@section('content')
<script language="javascript">
function password_setting()
{
	var strError = "";
	var current_password = $('#current_password').val();
	var new_password = $('#new_password').val();
	var confirm_new_password = $('#confirm_new_password').val();
	var _token = $('#_token').val();
	
	if(current_password=="") strError += "<li>Current password harus diisi</li>";
	if(new_password=="") strError += "<li>New password harus diisi</li>";
	if(confirm_new_password=="") strError += "<li>Confirm new password harus diisi</li>";
	
	if(strError=="")
	{
	
		if(new_password!=confirm_new_password)
		{
			$("#result_password").empty().append("<div class=\"alert alert-danger\"><li>New password dan Confirm new password tidak sama</li></div>").hide().fadeIn();
		}
		else
		{
			$.post("/auth/setting", { 
			current_password: current_password,
			new_password: new_password,
			confirm_new_password: confirm_new_password,
			setting: "password_setting",
			_token:_token,
			submit: "Update"
			} )
			.done(function( data ) {
			$("#result_password").empty().append(data).hide().fadeIn();
  			});
	
		}
		
	}
	else
	{
			$("#result_password").empty().append("<div class=\"alert alert-danger\">"+ strError +"</div>").hide().fadeIn();
	}
		
		
	return false;
}

</script>
<!-- /.col-lg-4 -->

	<div class="col-lg-4">
    <form method="post" action=""  onSubmit="return password_setting()">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Change password
			</div>
			<div class="panel-body">
				<p>
                <div id="result_password"></div>


    Current password : <input type="password" name="current_password" id="current_password" value="" class="form-control" />
    New Password : <input type="password" name="new_password" id="new_password" value="" class="form-control" />
    Confirm New Password : <input type="password" name="confirm_new_password" id="confirm_new_password" value="" class="form-control" />

                </p>
			</div>
			<div class="panel-footer">
            	<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>" />
				<input type="submit" name="submit" id="submit"  class="btn btn-lg btn-success btn-block" value="Update" />
			</div>
		</div>
        </form>
	</div>
<!-- /.col-lg-4 -->

@endsection
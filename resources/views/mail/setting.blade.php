@extends('sbadminv2.backend')
@section('title', 'Setting')
@section('user', $user->name )
@section('content')
<script language="javascript">

function pushover_setting()
{
	var pushover_user = $('#pushover_user').val();
	var pushover_app = $('#pushover_app').val();
	var _token = $('#_token').val();
	$.post("/mail/setting", { 
	pushover_user: pushover_user,
	pushover_app: pushover_app,
	setting: "pushover_setting",
	_token:_token,
	submit: "Update"
	} )
	.done(function( data ) {
			$("#result_pushover").empty().append(data).hide().fadeIn();
  	});
	
	
	return false;
}

function mail_setting()
{
	var mail_name = $('#mail_name').val();
	var mail_email = $('#mail_email').val();
	var _token = $('#_token').val();
	$.post("/mail/setting", { 
	mail_name: mail_name,
	mail_email: mail_email,
	setting: "mail_setting",
	_token:_token,
	submit: "Update"
	} )
	.done(function( data ) {
			$("#result_mail").empty().append(data).hide().fadeIn();
  	});
	
	
	return false;
}
</script>
<!-- /.col-lg-4 -->
	<div class="col-lg-4">
    <form method="post" action=""  onSubmit="return mail_setting()">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Email Setting
			</div>
			<div class="panel-body">
				<p>
                <div id="result_mail"></div>


    Name : <input type="text" name="mail_name" id="mail_name" value="{{$mail_name}}" class="form-control" />
    Email : <input type="text" name="mail_email" id="mail_email" value="{{$mail_email}}" class="form-control" />
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
<!-- /.col-lg-4 -->
	<div class="col-lg-4">
    <form method="post" action=""  onSubmit="return pushover_setting()">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Pushover Notification
			</div>
			<div class="panel-body">
				<p>
                <div id="result_pushover"></div>


    Pushover app key : <input type="text" name="pushover_app" id="pushover_app" value="{{$pushover_app}}" class="form-control" />
    Pushover user key : <input type="text" name="pushover_user" id="pushover_user" value="{{$pushover_user}}" class="form-control" />
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
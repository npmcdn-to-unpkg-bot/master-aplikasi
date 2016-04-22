<script language="javascript">
function postEdit()
{
	var api_key = $('#api_key').val();
	var api_secret = $('#api_secret').val();
	var phone = $('#phone').val();
	var service = $('#service').val();
	var _token = $('#_token').val();
	var id = $('#id').val();
	var table = $('#dataTables-example').DataTable();
	$.post("/message/account/edit", { 
	api_key: api_key,
	api_secret: api_secret,
	phone: phone,
	service: service,
	id: id,
	_token:_token,
	submit: "Update"
	} )
	.done(function( data ) {
    	if(data=="")
		{
			table.ajax.reload( null, false );
			$.fancybox.close();
		}
		else
		{
			$("#result").empty().append(data).hide().fadeIn();;
		}
  	});
	
	
	return false;
}
</script>
<div id="result"></div>
<form role="form" id="editForm" method="POST" action="" onSubmit="return postEdit()">
	<fieldset>
    	<div class="form-group">
        <label>Service</label>
        	<select id="service" class="form-control">
        		
                <option value="Twilio"
                @if($account->service=="Twilio")
                	selected
                @endif
                >Twilio</option>
        		<option value="Nexmo"
                @if($account->service=="Nexmo")
                	selected
                @endif
                >Nexmo</option>
                <option value="Telerivet"
                @if($account->service=="Telerivet")
                	selected
                @endif
                >Telerivet</option>
        	</select>
        </div>
        <div class="form-group">
        <label>Phone Number</label>
			<input type="text" id="phone" name="phone" class="form-control" value="{{$account->phone}}">
        </div>
        <div class="form-group">
        <label>API Key</label>
			<input type="text" id="api_key" name="api_key" class="form-control" value="{{$account->api_key}}">
        </div>
        <div class="form-group">
        <label>API Secret</label>
			<input type="text" id="api_secret" name="api_secret" class="form-control" value="{{$account->api_secret}}">
        </div>
        <input type="hidden" id="id" name="id" value="{{$account->id}}">
        <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
		<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Update">
	</fieldset>
</form>
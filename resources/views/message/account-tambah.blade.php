<script language="javascript">
function postAdd()
{
	
	var api_key = $('#api_key').val();
	var api_secret = $('#api_secret').val();
	var phone = $('#phone').val();
	var service = $('#service').val();
	var _token = $('#_token').val();
	var table = $('#dataTables-example').DataTable();
	$.post("/message/account/add", { 
	api_key: api_key, 
	api_secret: api_secret, 
	phone: phone, 
	service: service, 
	_token:_token, 
	submit: "Add" 
	} )
	.done(function( data ) {
    	if(data=="")
		{
			
			table.ajax.reload( null, false );
			$.fancybox.close();
		}
		else
		{
			$("#result").empty().append(data).hide().fadeIn();
		}
		
  	});
	
	
	return false;
}
</script>
<div id="result"></div>
<form role="form" id="editForm" method="POST" action="" onSubmit="return postAdd()">
	<fieldset>
    	<div class="form-group">
        <label>Service</label>
        	<select id="service" class="form-control">
                <option value="Twilio">Twilio</option>
        		<option value="Nexmo">Nexmo</option>
                <option value="Telerivet">Telerivet</option>
        	</select>
        </div>
        <div class="form-group">
        <label>Phone Number</label>
			<input type="text" id="phone" name="phone" class="form-control">
        </div>
        <div class="form-group">
        <label>API Key</label>
			<input type="text" id="api_key" name="api_key" class="form-control">
        </div>
        <div class="form-group">
        <label>API Secret</label>
			<input type="text" id="api_secret" name="api_secret" class="form-control">
        </div>
        
        <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
		<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Add">
	</fieldset>
</form>
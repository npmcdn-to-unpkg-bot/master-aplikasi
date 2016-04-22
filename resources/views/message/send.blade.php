
<link href="/bower_components/devbridge-autocomplete/content/styles.css" rel="stylesheet" />
<script src="/bower_components/devbridge-autocomplete/src/jquery.autocomplete.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
			$('#to').autocomplete({
   				serviceUrl: '/message/send/search',
				paramName: 'q',
    			onSelect: function (suggestion) {
        		//alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
				$('#to').val(suggestion.data);
				$('#text').focus();
    			}
			});
			
	
	})
	
	function clean()
	{
		$('#to').val('');
	}
</script>
<script language="javascript">
function send()
{
	var to = $('#to').val();
	var text = $('#text').val();
	var service = $('#service').val();
	var _token = $('#_token').val();
	$.post("/message/send", { 
	to: to,
	text: text,
	service: service,
	_token:_token,
	submit: "Send"
	} )
	.done(function( data ) {
		var hasil = data.split("|");
    	if(hasil[0]=="success")
		{
			window.location='/message/inbox/detail/'+ hasil[1];
		}
		else
		{
			$("#result").empty().append(hasil[1]).hide().fadeIn();;
		}
  	});
	
	
	return false;
}
</script>
<div id="result"></div>
<form method="post" action=""  onSubmit="return send()">
<fieldset>
	 <div class="form-group">
     <label>From</label>
    	<select name="service" id="service" class="form-control">
    		@foreach($services as $service)
    		<option value="{{$service->id}}">{{$service->phone}}</option>
    		@endforeach
    	</select>
     </div>
    <div class="form-group">
    <label>To</label>
    	<input type="text" name="to" id="to" autocomplete="off" onfocus="clean()" value="{{$to}}" class="form-control" />
    </div>
    <div class="form-group">
    <label>Message</label>
    	<textarea name="text" id="text" cols="45" rows="5" class="form-control"></textarea>
    </div><input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
    	<input type="submit" name="submit" id="submit"  class="btn btn-lg btn-success btn-block" value="Submit" />
</fieldset>  
</form>
	
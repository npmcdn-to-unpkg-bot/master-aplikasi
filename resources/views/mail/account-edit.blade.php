<script language="javascript">
function postEditAccount()
{
	var name = $('#name').val();
	var email = $('#email').val();
	var _token = $('#_token').val();
	var id = $('#id').val();
	var table = $('#dataTables-example').DataTable();
	$.post("/mail/account/edit", { name: name, email: email, id: id, _token:_token, submit: "Update" } )
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
<form role="form" id="editForm" method="POST" action="" onSubmit="return postEditAccount()">
	<fieldset>
		
        <div class="form-group">
        <label>Name</label>
			<input type="text" id="name" name="name" class="form-control" value="{{$account->name}}">
        </div>
        <div class="form-group">
        <label>Phone number</label>
			<input type="text" id="email" name="email" class="form-control" value="{{$account->email}}">
        </div>
        <input type="hidden" id="id" name="id" value="{{$account->id}}">
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
		<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Update">
	</fieldset>
</form>
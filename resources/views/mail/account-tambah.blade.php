<script language="javascript">
function postAddAccount()
{
	var name = $('#name').val();
	var email = $('#email').val();
	var _token = $('#_token').val();
	var id = $('#id').val();
	var table = $('#dataTables-example').DataTable();
	$.post("/mail/account/add", { name: name, email: email, _token:_token, submit: "Add" } )
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
<form role="form" id="addForm" method="POST" action="" onSubmit="return postAddAccount()">
	<fieldset>
		
        <div class="form-group">
        <label>Nama</label>
			<input type="text" id="name" name="name" class="form-control" >
        </div>
        <div class="form-group">
        <label>Email</label>
			<input type="text" id="email" name="email" class="form-control" >
        </div>
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
		<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Add">
	</fieldset>
</form>
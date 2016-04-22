<script language="javascript">
function postEditContact()
{
	var nama = $('#nama').val();
	var phone = $('#phone').val();
	var _token = $('#_token').val();
	var id = $('#id').val();
	var table = $('#dataTables-example').DataTable();
	$.post("/message/contact/edit", { nama: nama, phone: phone, id: id, _token:_token, submit: "Update" } )
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
<form role="form" id="editForm" method="POST" action="" onSubmit="return postEditContact()">
	<fieldset>
		
        <div class="form-group">
        <label>Name</label>
			<input type="text" id="nama" name="nama" class="form-control" value="{{$contact->nama}}">
        </div>
        <div class="form-group">
        <label>Phone number</label>
			<input type="text" id="phone" name="phone" class="form-control" value="{{$contact->phone}}">
        </div>
        <input type="hidden" id="id" name="id" value="{{$contact->id}}">
        <input type="hidden" id="_token" name="_token" value="<?php echo csrf_token(); ?>">
		<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Update">
	</fieldset>
</form>
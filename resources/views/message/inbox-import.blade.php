

<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload </b></div>
<div id="status"></div>
<script>
$(document).ready(function()
{
var settings = {
    url: "/message/inbox/import",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"xml",	
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDelete:false,
	formData: { _token: '{{csrf_token()}}' }
	}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
});

function importContactClose()
{
	var table = $('#dataTables-example').DataTable();
	table.ajax.reload();
	$.fancybox.close();
}
</script>
<br />
<input  class="btn btn-lg btn-success btn-block" value="Close" onclick="importContactClose();">
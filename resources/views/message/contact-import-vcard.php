

<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload </b></div>
<div id="status"></div>
<script>
$(document).ready(function()
{
var settings = {
    url: "/message/contact/import",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"vcf,vfc",	
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
       // alert((data));
    },
    showDelete:false,
	formData: { _token: '<?php echo csrf_token(); ?>' }
    
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
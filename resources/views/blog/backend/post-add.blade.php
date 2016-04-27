@extends('sbadminv2.backend')
@section('title', 'Add Photo Post')
@section('user', $user->name )
@section('content')
<script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="/bower_components/fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="/bower_components/fancyBox/source/jquery.fancybox.css" media="screen" />
	<script src="/bower_components/jquery-confirm/jquery.confirm.min.js"></script>
    <link href="/bower_components/jquery-uploadfile/css/uploadfile.css" rel="stylesheet">
	<script src="/bower_components/jquery-uploadfile/js/jquery.uploadfile.min.js"></script>
<script type="text/javascript" src="/bower_components/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
});
</script>

						@if (count($errors) > 0)
    					<div class="alert alert-danger">
        					<ul>
            					@foreach ($errors->all() as $error)
                				<li>{{ $error }}</li>
            					@endforeach
        					</ul>
    					</div>
						@endif
<form method="post" action="/blog/post/add">
<!-- input type="text" name="judul" class="form-control" placeholder="Judul"><br>
<select name="tipe_post" class="form-control">
<option value="post">Post</option>
<option value="page">Page</option>
</select><br>
<select name="tipe_konten" class="form-control">
<option value="gallery">Gallery</option>
<option value="text">Text</option>
</select><br -->
<input type="hidden" name="tipe_post" value="post">
<input type="hidden" name="tipe_konten" value="gallery">
<input type="text" name="layout" class="form-control" placeholder="Layout"><br>
<div class='input-group date' id='datetimepicker1'>
                    <input type="text" name="tanggal" readonly value="{{$tanggal}}" id="date1" class="form-control">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
 		<script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
					format: 'YYYY-MM-DD HH:mm:00',
					showTodayButton: true,
					showClose: true,
					ignoreReadonly: true
				});
            });
        </script>           
<br>
<div id="status"></div>
<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload </b></div>

<script>
$(document).ready(function()
{
var settings = {
    url: "/blog/image/add",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"jpg,jpeg",	
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
		
    },
    showDelete:true,
	formData: { key: '{{$key}}' },
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        
		$.post("/blog/image/delete",{op:"delete",name:data[i]},
        function(resp, textStatus, jqXHR)
        {
            //Show Message  
            //$("#status").append("<div>File Deleted</div>");      
        });
     }      
    pd.statusbar.hide();
	}
	
}
var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
});

</script>
<br>
<!-- textarea name="konten" style="width:100%"></textarea><br -->
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
<input type="hidden" name="key" value="{{$key}}">
<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Save">
</form>
@endsection
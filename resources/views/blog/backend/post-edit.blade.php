@extends('sbadminv2.backend')
@section('title', 'Edit Photo Post')
@section('user', $user->name )
@section('content')
<script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
  

    <link href="/bower_components/jquery-uploadfile/css/uploadfile.css" rel="stylesheet">
	<script src="/bower_components/jquery-uploadfile/js/jquery.uploadfile.min.js"></script>


						@if (count($errors) > 0)
    					<div class="alert alert-danger">
        					<ul>
            					@foreach ($errors->all() as $error)
                				<li>{{ $error }}</li>
            					@endforeach
        					</ul>
    					</div>
						@endif
<form method="post" action="/blog/post/edit">
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
	acceptFiles:"image/*",
	uploadStr:"<i class=\"fa fa-camera fa-fw\"></i> Capture",
	onSuccess:function(files,data,xhr)
    {
		
    },
    showDelete:true,
	formData: { key: '{{$key}}', _token: '{{csrf_token()}}' },
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        
		$.post("/blog/image/delete",{_token:"{{csrf_token()}}",name:data[i]},
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
<input type="hidden" name="tipe_post" value="post">
<input type="hidden" name="tipe_konten" value="gallery">
<input type="text" name="layout" value="{{$result->layout}}" class="form-control"><br>			
                <div class='input-group date' id='datetimepicker1'>
                    <input type="text" name="tanggal" readonly value="{{$result->tanggal}}" id="date1" class="form-control">
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

<!-- textarea name="konten" style="width:100%">{{$result->konten}}</textarea><br -->
<input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
<input type="hidden" name="key" value="{{$key}}">
<input type="hidden" name="id" value="{{$id}}">
<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Save">
</form>
@endsection
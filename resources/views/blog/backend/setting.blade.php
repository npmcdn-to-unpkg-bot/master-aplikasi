@extends('sbadminv2.backend')
@section('title', 'Blog Setting')
@section('user', $user->name )
@section('content')
<link href="/bower_components/jquery-uploadfile/css/uploadfile.css" rel="stylesheet">
<script src="/bower_components/jquery-uploadfile/js/jquery.uploadfile.min.js"></script>
<div class="col-lg-12">
<form method="post" action="/blog/setting">
<div class="panel panel-primary">
<div class="panel-heading">
Title, Description, Header, and Socmed
</div>
<div class="panel-body">
<input type="text" name="judul1" class="form-control" value="{{ $setting->judul1 }}" placeholder="Judul">
<br>
<input type="text" name="judul2" class="form-control" value="{{ $setting->judul2 }}" placeholder="Judul">
<br>
<textarea name="deskripsi" class="form-control" style="height:200px;">{{ $setting->deskripsi }}</textarea>
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
	allowDuplicates: false,
	multiple: false,
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
<input type="text" name="facebook" class="form-control" value="{{ $setting->facebook }}" placeholder="Facebook username">
<br>
<input type="text" name="twitter" class="form-control" value="{{ $setting->twitter }}" placeholder="Twitter username">
<br>
<input type="text" name="instagram" class="form-control" value="{{ $setting->instagram }}" placeholder="Instagram username">
<br>
</div>
<div class="panel-footer">
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
<input type="hidden" name="key" value="{{$key}}">
<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Save">
</div>
</div>
</form>
</div>
@endsection
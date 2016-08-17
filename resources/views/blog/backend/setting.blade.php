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
<div class="form-group">
<b>Judul 1 :</b>
<input type="text" name="judul1" class="form-control" value="{{ $setting->judul1 }}" placeholder="Judul">
</div>
<div class="form-group">
<b>Judul 2 :</b>
<input type="text" name="judul2" class="form-control" value="{{ $setting->judul2 }}" placeholder="Judul">
</div>
<div class="form-group">
<b>Deskripsi :</b>
<textarea name="deskripsi" class="form-control" style="height:200px;">{{ $setting->deskripsi }}</textarea>
</div>
<div class="form-group">
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
	formData: { key: 'header', _token: '{{csrf_token()}}' },
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
</div>
<div class="form-group">
<b>Facebook :</b>
<input type="text" name="facebook" class="form-control" value="{{ $setting->facebook }}" placeholder="Facebook link">
</div>
<div class="form-group">
<b>Twitter :</b>
<input type="text" name="twitter" class="form-control" value="{{ $setting->twitter }}" placeholder="Twitter link">
</div>
<div class="form-group">
<b>Instagram :</b>
<input type="text" name="instagram" class="form-control" value="{{ $setting->instagram }}" placeholder="Instagram link">
</div>
<div class="form-group">
<b>Path :</b>
<input type="text" name="path" class="form-control" value="{{ $setting->path }}" placeholder="Path link">
</div>
<div class="form-group">
<b>Github :</b>
<input type="text" name="github" class="form-control" value="{{ $setting->github }}" placeholder="Github link">
</div>
</div>
<div class="panel-footer">
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
<input type="hidden" name="key" value="header">
<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Save">
</div>
</div>
</form>
</div>
@endsection
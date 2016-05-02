@extends('sbadminv2.backend')
@section('title', 'Email Compose')
@section('user', $user->name )
@section('content')

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
<form method="post" action="/mail/compose">
<b>To :</b><br />
<input type="text" name="to" class="form-control" value="{{ $from }}"><br>
<b>Subject :</b><br />
<input type="text" name="subject" class="form-control" value="{{ $subject }}"><br> 
<b>Message :</b><br />
<textarea name="konten" style="width:100%">{{ $replay_message }}</textarea><br>  
<strong>Attachment :</strong>
<br>
<div id="status"></div>
<div id="mulitplefileuploader"><b class="fa fa-plus"> Upload </b></div>

<script>
$(document).ready(function()
{
var settings = {
    url: "/mail/attach/add",
    dragDrop:true,
    fileName: "myfile",
    allowedTypes:"*",	
    returnType:"json",
	onSuccess:function(files,data,xhr)
    {
		
    },
    showDelete:true,
	formData: { key: '{{$key}}', _token: '{{csrf_token()}}' },
    deleteCallback: function(data,pd)
	{
    for(var i=0;i<data.length;i++)
    {
        
		$.post("/mail/attach/delete",{_token:"{{csrf_token()}}",name:data[i]},
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

<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
<input type="hidden" name="key" value="{{$key}}">
<input  class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Send">
</form>
@endsection
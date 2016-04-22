@extends('sbadminv2.backend')
@section('title', 'Email Detail')
@section('user', $user->name )
@section('content')

<script type="text/javascript" src="/bower_components/fancyBox/source/jquery.fancybox.pack.js"></script>
<link rel="stylesheet" type="text/css" href="/bower_components/fancyBox/source/jquery.fancybox.css" media="screen" />
<script src="/bower_components/jquery-confirm/jquery.confirm.min.js"></script>
<script type="text/javascript">
function hapusAction(id)
	{
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/inbox/delete/'+ id
		}).done(function( msg ) {
			window.location='/mail/inbox';
		});	
	}
	
	function hapus(id)
	{
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan menghapusnya?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					hapusAction(id);
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
	}
</script>
<div>
	<b>From : </b>{{ $result->from }}
</div>
<div>
	<b>To :</b> {{ $result->recipient }}
</div>
<div>
	<b>Date :</b> {{ date('Y-m-d H:i:s', $result->timestamp) }}
</div>
<hr>
<?php print(html_entity_decode(nl2br($result->body_plain))); ?>
<hr>
@if($result->attachment_count>0)
<hr>
@foreach($result->attachments as $attachment)
<a href="/mail/download/attachment/{{ $attachment->id }}"> {{ $attachment->original_filename }} </a><br />
@endforeach
@endif
<button id="btn-del" type="button" onClick="hapus('{{$result->id}}')" class="btn btn-danger btn-sm"><b class="fa fa-trash-o"> Delete </b></button>
@endsection
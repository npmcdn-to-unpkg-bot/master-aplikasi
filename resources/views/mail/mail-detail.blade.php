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
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/trash/'+ id
		}).done(function( msg ) {
			window.location='/mail/{{$type}}';
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
	
	function moveAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/move/'+ id
		}).done(function( msg ) {
			window.location='/mail/{{$type}}';
		});	
	}
	
	function move(id)
	{
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan memindahnya?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					moveAction(id);
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
	}
	
	function delAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/delete/'+ id
		}).done(function( msg ) {
			window.location='/mail/{{$type}}';
		});	
	}
	
	function del(id)
	{
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan menghapusnya secara permanen?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					delAction(id);
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
<b>{{ $result->subject }}</b>
<hr>
<?php print(html_entity_decode(nl2br($result->body_plain))); ?>
<hr>
@if($result->attachment_count>0)
<hr>
@foreach($result->attachments as $attachment)
<a href="/mail/download/attachment/{{ $attachment->id }}"> {{ $attachment->original_filename }} </a><br />
@endforeach
<hr>
@endif

<?= $button ?>
@endsection
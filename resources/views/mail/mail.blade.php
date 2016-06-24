@extends('sbadminv2.backend')
@section('title', 'Email '. ucfirst($type))
@section('user', $user->name )
@section('content')

	<script type="text/javascript" src="/bower_components/fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="/bower_components/fancyBox/source/jquery.fancybox.css" media="screen" />
	<script src="/bower_components/jquery-confirm/jquery.confirm.min.js"></script>
    <link href="/bower_components/jquery-uploadfile/css/uploadfile.css" rel="stylesheet">
	<script src="/bower_components/jquery-uploadfile/js/jquery.uploadfile.min.js"></script>
	<script type="text/javascript">
	
	
	jQuery(document).ready(function($) {	
			var table = $('#dataTables-example').DataTable(
			{
				"processing": true,
       			"serverSide": true,
        		"ajax": '/mail/{{$type}}/data',
				columns: [
            		{data: 'sender', name: 'sender', orderable: false, "width": "60%"},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center', "width": "40%"}
            		
        		],
				"pagingType": "full_numbers"
			});
			
			
	});
	
	function moveAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/move/'+ id
		}).done(function( msg ) {
			table.ajax.reload( null, false );
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
	
	@if($type=="trash")
	
	function emptyTrashAction()
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/trash/empty'
		}).done(function( msg ) {
			table.ajax.reload( null, false );
		});	
	}
	
	function emptyTrash()
	{
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan mengkosongkan trash?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					emptyTrashAction();
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
			table.ajax.reload( null, false );
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
	
	@else
	
	@if($type=="spam")
	
	function emptySpamAction()
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/spam/empty'
		}).done(function( msg ) {
			table.ajax.reload( null, false );
		});	
	}
	
	function emptySpam()
	{
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan mengkosongkan spam?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					emptySpamAction();
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
	}
	
	@endif
	
	function hapusAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/mail/trash/'+ id
		}).done(function( msg ) {
			table.ajax.reload( null, false );
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
	
	@endif
	
</script>
   <div style="float:right; margin-bottom:11px;">
    	@if($type=="trash")
        <button type="button" class="btn btn-danger btn-sm" onClick="emptyTrash()"><b class="fa fa-trash-o"> Empty Trash </b></button>
        @endif
        @if($type=="spam")
        <button type="button" class="btn btn-danger btn-sm" onClick="emptySpam()"><b class="fa fa-trash-o"> Empty Spam </b></button>
        @endif
        
    </div>
							<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>

@endsection
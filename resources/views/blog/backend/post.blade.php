@extends('sbadminv2.backend')
@section('title', 'Photo Post')
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
        		"ajax": '/blog/post/data',
				"order": [[ 1, "desc" ]],
				columns: [
            		{data: 'slug', name: 'slug'},
					{data: 'tanggal', name: 'tanggal'},
					{data: 'layout', name: 'layout'},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
            		
        		],
				"pagingType": "full_numbers"
			});
			
			
	});
	
	function publish(id, status)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/blog/post/publish/'+ id +'/'+ status
		}).done(function( msg ) {
			table.ajax.reload( null, false );
		});	
	}
	
	function hapusAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/blog/post/delete/'+ id
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
</script>
   
							<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Slug</th>
                                            <th>Tanggal</th>
                                            <th>Layout</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>

@endsection
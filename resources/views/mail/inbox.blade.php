@extends('sbadminv2.backend')
@section('title', 'Email')
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
            		{data: 'from_sender', name: 'from_sender', orderable: false},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: 'alignRight'}
            		
        		],
				"pagingType": "full_numbers"
			});
			
			
	});
	
	function hapusAction(id)
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
   <div style="float:right; margin-bottom:11px;">
    	
        <button type="button" class="btn btn-primary btn-sm" onClick="window.location='/mail/compose'"><b class="fa fa-plus"> Compose </b></button>
        
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
@extends('sbadminv2.backend')
@section('title', 'Account')
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
        		"ajax": '/mail/account/data',
				columns: [
            		{data: 'name', name: 'name'},
            		{data: 'email', name: 'email'},
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
     	url: '/mail/account/delete/'+ id
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
<script language="javascript">
function addAccount()
{
	$.fancybox({
        type: 'ajax',
		'width': 300,
		'height': 250,
		'autoSize' : false,
        href: '/mail/account/add'
    });	
}
function editAccount(id)
{
	 $.fancybox({
        type: 'ajax',
		'width': 300,
		'height': 250,
		'autoSize' : false,
        href: '/mail/account/edit/'+ id
    });	
}
</script>
    <div style="float:right; margin-bottom:11px;">
    	
        <button type="button" class="btn btn-primary btn-sm" onclick="addAccount()"><b class="fa fa-plus"> Add Contact </b></button>
        
    </div>
							<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                           
@endsection
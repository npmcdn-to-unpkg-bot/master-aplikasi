@extends('sbadminv2.backend')
@section('title', 'Contact')
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
        		"ajax": '/message/contact/data',
				columns: [
            		{data: 'nama', name: 'nama'},
            		{data: 'phone', name: 'phone'},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: 'alignRight'}
            		
        		],
				"pagingType": "full_numbers"
			});
			
			
	});
	
	function sendSMS(number)
	{
	$.fancybox({
        type: 'ajax',
		'width': 400,
		'height': 400,
		'autoSize' : false,
        href: '/message/send?to='+ number
    });	
	}


	function hapusAction(id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/message/contact/delete/'+ id
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
function emptyContactAction()
{
	var table = $('#dataTables-example').DataTable();
	$.ajax({
     async: false,
     type: 'GET',
     url: '/message/contact/empty'
     
	}).done(function( msg ) {
    	table.ajax.reload();
  	});	
}
function emptyContact()
{
				
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin untuk mengkosongkan kontak?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					emptyContactAction();
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
				
				
}
function importContact()
{
	$.fancybox({
        type: 'ajax',
		'width': 420,
		'height': 200,
		'autoSize' : false,
        href: '/message/contact/import'
    });	
}
function addContact()
{
	$.fancybox({
        type: 'ajax',
		'width': 300,
		'height': 250,
		'autoSize' : false,
        href: '/message/contact/add'
    });	
}
function editContact(id)
{
	 $.fancybox({
        type: 'ajax',
		'width': 300,
		'height': 250,
		'autoSize' : false,
        href: '/message/contact/edit/'+ id
    });	
}
</script>
    <div style="float:right; margin-bottom:11px;">
    	<button type="button" class="btn btn-primary btn-sm" onclick="importContact()"><b class="fa fa-plus"> Import vCard </b></button>
        <button type="button" class="btn btn-primary btn-sm" onclick="addContact()"><b class="fa fa-plus"> Add Contact </b></button>
        <button type="button" id="btn-empty" class="btn btn-danger btn-sm" onclick="emptyContact()" ><b class="fa fa-trash-o"> Empty Contact </b></button>
    </div>
							<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Phone</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                           
@endsection
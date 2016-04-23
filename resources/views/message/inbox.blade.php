@extends('sbadminv2.backend')
@section('title', 'Inbox')
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
        		"ajax": '/message/inbox/data',
				columns: [
            		{data: 'body', name: 'body'},
					{data: 'action', name: 'action', orderable: false, searchable: false, className: 'alignRight'},
					
					{data: 'nama', name: 'nama', visible: false}
            		
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
     	url: '/message/inbox/deleteMessage/'+ id
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
function addData()
{
	$.fancybox({
        type: 'ajax',
		'width': 500,
		'height': 200,
		'autoSize' : false,
        href: '/message/inbox/import'
    });	
}

function sendSMS()
{
	$.fancybox({
        type: 'ajax',
		'width': 400,
		'height': 400,
		'autoSize' : false,
        href: '/message/send'
    });	
}
</script>
   <div style="float:right; margin-bottom:11px;">
	<button type="button" class="btn btn-primary btn-sm" onclick="sendSMS()"><b class="fa fa-edit"> Send SMS </b></button>
    <button type="button" class="btn btn-primary btn-sm" onclick="addData()"><b class="fa fa-plus"> Import SMS </b></button>
</div>
							<div class="dataTable_wrapper">
                                <table width="100%" class="table table-hover" id="dataTables-example">
                                    <thead>
                                        <tr style="display:none">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	
                                    </tbody>
                                </table>
                            </div>
                           
@endsection
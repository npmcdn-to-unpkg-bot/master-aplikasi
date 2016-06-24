@extends('sbadminv2.backend')
@section('title', 'Account')
@section('user', $user->name )
@section('content')
	<script type="text/javascript" src="/bower_components/fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="/bower_components/fancyBox/source/jquery.fancybox.css" media="screen" />
	<script src="/bower_components/jquery-confirm/jquery.confirm.min.js"></script>
    <link href="/js/upload/uploadfile.css" rel="stylesheet">
	<script src="/js/upload/jquery.uploadfile.min.js"></script>
	<script type="text/javascript">
	
	
	jQuery(document).ready(function($) {	
			var table = $('#dataTables-example').DataTable(
			{
				"aoColumns" : [   
        			{  },
					{  },   
        			{ sClass: "text-center" }  
    			],
				"ajax": "/message/account/list",
				"columnDefs": [ {"bSortable": false,"targets": -1,"data": null,"defaultContent": "<button id=\"btn-edit\" type=\"button\" class=\"btn btn-success btn-sm\"><b class=\"fa fa-pencil\"> Edit </b></button>&nbsp;<button id=\"btn-del\" type=\"button\" class=\"btn btn-danger btn-sm\"><b class=\"fa fa-trash-o\"> Delete </b></button>"} ]
				
			});
			
			$('#dataTables-example tbody').on( 'click', '#btn-del', function () {
				
				var idObject = $(this).parents('tr');
				var data = table.row( $(this).parents('tr') ).data();
				
				$.confirm({
				title: "Perhatian",
        		text: "Apakah anda yakin akan menghapusnya?",
				confirmButton: "Ya",
    			cancelButton: "Batal",
				confirmButtonClass: "btn-danger",
    			cancelButtonClass: "btn-default",
        		confirm: function(button) {
					hapus(idObject,data[2]);
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
				
			} );

			$('#dataTables-example tbody').on( 'click', '#btn-edit', function () {
				var data = table.row( $(this).parents('tr') ).data();
				editData(data[2]);
			});
			
			
	
	})
	
	
	

	

	</script>
<script language="javascript">
function hapus(idObject,id)
	{
		var table = $('#dataTables-example').DataTable();
		$.ajax({
     	async: false,
     	type: 'GET',
     	url: '/message/account/delete/'+ id
		}).done(function( msg ) {
			table.row(idObject).remove().draw(false);
		});	
			
	}
function addData()
{
	$.fancybox({
        type: 'ajax',
		'width': 500,
		'height': 500,
		'autoSize' : false,
        href: '/message/account/add'
    });	
}
function editData(id)
{
	 $.fancybox({
        type: 'ajax',
		'width': 500,
		'height': 500,
		'autoSize' : false,
        href: '/message/account/edit/'+ id
    });	
}
</script>
    <div style="float:right; margin-bottom:11px;">
    	
        <button type="button" class="btn btn-primary btn-sm" onclick="addData()"><b class="fa fa-plus"> Add Account </b></button>
        
    </div>
							<div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Provider</th>
                                            <th>Phone</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                           
@endsection
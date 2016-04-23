@extends('sbadminv2.backend')
@section('title', 'Inbox Detail')
@section('user', $user->name )
@section('content')
<style media="screen">
/* THE SPEECH BUBBLE
------------------------------------------------------------------------------------------------------------------------------- */

.triangle-isosceles {
  position:relative;
  padding:15px;
  margin:1em 0 3em;
  color:#000;
  background:#f3961c; /* default background for browsers without gradient support */
  /* css3 */
  background:-webkit-gradient(linear, 0 0, 0 100%, from(#EEEEEE), to(#999999));
  background:-moz-linear-gradient(#EEEEEE, #999999);
  background:-o-linear-gradient(#EEEEEE, #999999);
  background:linear-gradient(#EEEEEE, #999999);
  -webkit-border-radius:10px;
  -moz-border-radius:10px;
  border-radius:10px;
}


/* Variant : for left/right positioned triangle
------------------------------------------ */

.triangle-isosceles.left {
  margin-left:50px;
  background:#BBBBBB;
}

/* Variant : for right positioned triangle
------------------------------------------ */

.triangle-isosceles.right {
  margin-right:50px;
  background:-webkit-gradient(linear, 0 0, 0 100%, from(#BBBBBB), to(#EEEEEE));
  background:-moz-linear-gradient(#BBBBBB, #EEEEEE);
  background:-o-linear-gradient(#BBBBBB, #EEEEEE);
  background:linear-gradient(#BBBBBB, #EEEEEE);
}

/* THE TRIANGLE
------------------------------------------------------------------------------------------------------------------------------- */

/* creates triangle */
.triangle-isosceles:after {
  content:"";
  position:absolute;
  bottom:-15px; /* value = - border-top-width - border-bottom-width */
  left:50px; /* controls horizontal position */
  border-width:15px 15px 0; /* vary these values to change the angle of the vertex */
  border-style:solid;
  border-color:#BBBBBB transparent;
  /* reduce the damage in FF3.0 */
  display:block;
  width:0;
}

/* Variant : top
------------------------------------------ */

.triangle-isosceles.top:after {
  top:-15px; /* value = - border-top-width - border-bottom-width */
  right:50px; /* controls horizontal position */
  bottom:auto;
  left:auto;
  border-width:0 15px 15px; /* vary these values to change the angle of the vertex */
  border-color:#BBBBBB transparent;
}

/* Variant : left
------------------------------------------ */

.triangle-isosceles.left:after {
  top:16px; /* controls vertical position */
  left:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  border-width:10px 50px 10px 0;
  border-color:transparent #BBBBBB;
}

/* Variant : right
------------------------------------------ */

.triangle-isosceles.right:after {
  top:16px; /* controls vertical position */
  right:-50px; /* value = - border-left-width - border-right-width */
  bottom:auto;
  left:auto;
  border-width:10px 0 10px 50px;
  border-color:transparent #BBBBBB;
}

</style>
<script src="/js/jquery.confirm/jquery.confirm.min.js"></script>
<script language="javascript">
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
					
					$.ajax({
     				async: false,
     				type: 'GET',
     				url: '/message/inbox/delete/'+ id
					}).done(function( msg ) {
						//table.row(idObject).remove().draw(false);
						$('#row-'+id).fadeOut(300, function() { $(this).remove(); });
					});
					
        		},
       			cancel: function(button) {
            		//alert("You cancelled.");
        		}
    			});
}
</script>

<div>             
<table border="0" class="table" cellspacing="2" cellpadding="1">
	<tr>
    	<td>
        	<i class="fa fa-fw fa-envelope-o"></i> <a href="/message/inbox">Inbox</a>&nbsp;&nbsp;
            <span class="glyphicon glyphicon-arrow-right"></span>&nbsp;&nbsp;<b>{{$from}}</b>
 		</td>
   </tr>
@for ($i = 0; $i < $jml; $i++)
  <tr id="row-{{$data[$i]['id']}}">
    <td>
    @if($data[$i]['type']==2)
    	<div style='margin-bottom:0px;margin-top:0px;' class='triangle-isosceles right'>
    @else
        <div class='triangle-isosceles left'  style='margin-bottom:0px;margin-top:0px;'>
    @endif
    		<div style="font-size:11px;white-space: nowrap;color:#555555; text-align:right; margin-bottom:0px; float:right; margin-left:5px;">
            	<a class="confirm" href="#" onclick="hapus('{{$data[$i]['id']}}')"><p class="fa fa-trash-o"></p></a>
            </div>
            
			{{$data[$i]['body']}}
            
    		<div style="font-size:11px;white-space: nowrap;color:#555555">
				{{$data[$i]['date']}}
    			&nbsp;
				@if($data[$i]['phone']!="")
    				<br />
                    {{$data[$i]['phone']}}
    			@endif   
    		</div>
              
		</div>
	</td>
  </tr>
@endfor
</table>
</div>
<script language="javascript">
function send()
{
	var to = $('#to').val();
	var text = $('#text').val();
	var service = $('#service').val();
	var _token = $('#_token').val();
	$.post("/message/send", { 
	to: to,
	text: text,
	service: service,
	_token:_token,
	submit: "Send"
	} )
	.done(function( data ) {
    	var hasil = data.split("|");
    	if(hasil[0]=="success")
		{
			window.location='/message/inbox/detail/'+ hasil[1];
		}
		else
		{
			$("#result").empty().append(hasil[1]).hide().fadeIn();;
		}
  	});
	
	
	return false;
}
</script>
<div id="result"></div>
<form method="post" action=""  onSubmit="return send()">
<fieldset>
	 <div class="form-group">
     <label>From</label>
    	<select name="service" id="service" class="form-control">
    		@foreach($services as $service)
    		<option value="{{$service->id}}">{{$service->phone}}</option>
    		@endforeach
    	</select>
   </div>
    <div class="form-group">
    <label>To</label>
    	<textarea name="text" id="text" cols="45" rows="5" class="form-control"></textarea>
    <input type="hidden" name="to" id="to" autocomplete="off" value="{{$address->address}}" class="form-control" />
    </div>
    <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">   
    <input type="submit" name="submit" id="submit"  class="btn btn-lg btn-success btn-block" value="Submit" />
</fieldset>
</form>
@endsection
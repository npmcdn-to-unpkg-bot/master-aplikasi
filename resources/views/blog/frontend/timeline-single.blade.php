@extends('sbadminv2.frontend')
@section('content')
    
    <script type="text/javascript" src="/bower_components/fancyBox/source/jquery.fancybox.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="/bower_components/fancyBox/source/jquery.fancybox.css" media="screen" />
    
<!-- Photogrid -->
<link href="/bower_components/photoset-grid/css/main.css" rel="stylesheet">
<script type="text/javascript" src="/bower_components/photoset-grid/jquery.photoset-grid.min.js"></script>



<!-- ################################################################### -->

 <section id="section"  style="background-color:#e9f0f5; max-width:500px; margin:20px auto;">
 <div style="margin-left:10px; margin-right:10px;">
 	<div class="photoset-grid" style=" max-width:500px; visibility:hidden;" data-layout="{{$last->layout}}">
    				<?php
					$a = $last->layout;
					$b = str_split($a);
					$c = 0 ;
					$e = 0 ;
					?>
    	@foreach($last->attachments as $attachment)
        				<?php
						$e++;
						$d = $b[$c]; // 2
						if($e==$d)
						{
							$c++;
							$e=0;
						}
						if($d>1)
						{
						?>
						<img class="image-photo" src="{{ str_replace('image/upload/','image/upload/c_fill,w_250/',$attachment->secure_url) }}" data-highres="{{ $attachment->secure_url }}" data-lightbox="image-{{$last->id}}">
						<?php	
						}
						else
						{
						?>
                    	<img class="image-photo" src="{{ str_replace('image/upload/','image/upload/c_fill,w_500/',$attachment->secure_url) }}" data-highres="{{ $attachment->secure_url }}" data-lightbox="image-{{$last->id}}">
                        <?php
						}
						?>
    	@endforeach
    </div>
    @if(!empty($last->konten))
    <div style="padding: 5px;
	box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.39);
	-moz-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.39);
	-webkit-box-shadow: 0px 0px 3px rgba(0, 0, 0, 0.39);
	background-color:#fff;
	margin: 5px 0px 5px 0px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;">{{ $last->konten }}</div>
    @endif
    <div style="margin-top:20px;">
    <p>Click button below to open timeline</p>
    <a href="/" class="btn btn-primary btn-xs page-scroll">Open Timeline</a>
    </div>
    
    
</div>
</section>
                
<!-- ################################################################### -->
    <script type="text/javascript">
		function photogrid()
		{
			$('.photoset-grid').photosetGrid({
				borderColor: '#FFFFFF',
				highresLinks: true,
				borderWidth: '2px',
				gutter: '2px',
				borderActive: true,
				onInit: function(){},
    			onComplete: function(){
        
					$('.photoset-grid').css({
            		'visibility': 'visible'
        			});

    			}
 			});
		}
		photogrid();
	</script>
    <link href="/bower_components/lightbox2/dist/css/lightbox.min.css" rel="stylesheet">
	<script src="/bower_components/lightbox2/dist/js/lightbox.min.js"></script>
	<script type="text/javascript">
    	lightbox.option({
      		'resizeDuration': 100,
      		'wrapAround': true
    	})
	</script>
    
@endsection

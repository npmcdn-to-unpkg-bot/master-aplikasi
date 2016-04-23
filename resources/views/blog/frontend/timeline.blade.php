@extends('sbadminv2.frontend')
@section('title', 'Historia Crux')
@section('content')
<style>
.intro-header {
  background-color: #000000;
  background: no-repeat center center;
  background-attachment: scroll;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  -o-background-size: cover;
  margin-bottom: 0px;
}
.intro-header .site-heading {
  padding: 100px 0 50px;
  color: white;
  max-width:700px;
  margin:0 auto;
}
@media only screen and (min-width: 768px) {
  .intro-header .site-heading {
    padding: 150px 0;
  }
  .intro-header .site-heading h1{
    font-size: 80px;
  }
}
.intro-header .site-heading {
  text-align: center;
}
.intro-header .site-heading h1 {
  margin-top: 0px;
  font-size: 50px;
  text-shadow: 2px 2px 3px rgba(0,0,0,0.6);
  filter: alpha(opacity=60);
}


.transbox {
    margin: 30px;
	border-radius: 10px;
	color: #ffffff;
    background-color: #000000;
    border: 0px;
    background: rgba(0,0,0,0.6);
	filter: alpha(opacity=60);
}


</style>	
    
	<!-- Infinite Scroll -->
    <link href="/bower_components/jquery-infinite-scroll/main.css" rel="stylesheet">
	<script src="/bower_components/jquery-infinite-scroll/jquery.infinitescroll.min.js"></script>
    
    <!-- Photogrid -->
    <link href="/bower_components/photoset-grid/css/main.css" rel="stylesheet">
	<script type="text/javascript" src="/bower_components/photoset-grid/jquery.photoset-grid.min.js"></script>
    
    <!-- Images Loaded -->
	<script type="text/javascript" src="/bower_components/imagesloaded/imagesloaded.pkgd.min.js"></script>
    
<!-- ################################################################### -->

	<header class="intro-header" style="background-image: url('/front-header.jpg')">
        
                
                    <div class="site-heading">
                    <div class="transbox">
                        <h1>Historia Crux</h1>
<hr style="max-width:50px;border-color: #f05f40;border-width: 3px;">
<p style="margin-left:15px; margin-right:15px; margin-bottom:45px;">Time flows in circles. Until all possibilities have been tested, it will remain locked in this arc for all eternity. Here, a moment in history endlessly repeats itself. Perhaps the same tale has been acted out in the same place at the same time for eons beyond counting.</p>
                    </div>
                    </div>
                
            
    </header>
 
<!-- ################################################################### -->

 <section id="section"  style="background-color:#e9f0f5; max-width:780px; margin:0 auto;">
 	<ul class="timeline">
	<?php
	$i = 1;
	$tanggal = "";
	$last_tanggal=strtotime($last->tanggal);
	$month=date("F",$last_tanggal);
	$year=date("Y",$last_tanggal);
	$last_tanggal = strtoupper($month ." ". $year);
	?>
    @foreach($results as $result)
    	<?php
		$time=strtotime($result->tanggal);
		$day=date("d",$time);
		$month=date("F",$time);
		$MONTH=date("M",$time);
		$year=date("Y",$time);
		$tanggal2 = strtoupper($month ." ". $year);
		if(($tanggal!=$tanggal2))
		{
			$tanggal = $tanggal2;
			?>
			<li class="test"><div class="tldate"><?= $tanggal ?></div></li>
			<?php	
		}
		$style = ' class="test"';
		if($i % 2 == 0) $style = ' class="timeline-inverted test"';
		?>
		<li<?= $style ?>>
			<div class="timeline-badge success">
				<span class="timeline-day"><?= $day ?></span>
				<span class="timeline-month"><?= strtoupper($MONTH) ?></span>
			</div>
			<div class="timeline-panel" style="background-color:#FFFFFF; margin-right:4px; margin-left:4px;">
				<div class="timeline-heading">
					<p class="text-muted text-left">
					<i class="fa fa-clock-o"></i> <?= timeAgo($result->tanggal) ?>
					</p>
				</div>
				<div class="timeline-body">
                
					<div class="photoset-grid" style=" max-width:600px; visibility:hidden" data-layout="{{$result->layout}}">
                    @foreach($result->attachments as $attachment)
                    	<img class="image-photo" src="{{ str_replace('image/upload/','image/upload/c_fill,w_295/',$attachment->secure_url) }}" data-highres="{{ $attachment->secure_url }}" data-lightbox="image-{{$result->id}}">
                    @endforeach
                    </div>
                    
                </div>
            </div>
         </li>
         <?php
		 $i++;
		 ?>
	@endforeach
</ul>
</section> 

<div style=" visibility:hidden">
	{!! $results->links() !!}
</div>   
                        
<!-- ################################################################### -->

	<a href="#0" class="cd-top">Top</a>
    <link href="/codyhouse/back-to-top/css/style.css" rel="stylesheet">
    <script src="/codyhouse/back-to-top/js/main.js"></script>
	
  	<script>
    $(function(){
      	var $container = $('.timeline');
      	$container.infinitescroll({
        navSelector  : '.pagination',    // selector for the paged navigation
      	nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.test',     		 // selector for all items you'll retrieve
        loading: {
          finishedMsg: "You've reached the end of the time",
          img: '/bower_components/jquery-infinite-scroll/loading.gif',
		  msgText: "<em>Loading the next set of posts...</em>"
          }
        },
        function( newElements ) {
		  $('.timeline').infinitescroll('pause');
		  $('.image-photo').attr('height','50');
		  $('.image-photo').attr('width','50');
		  
		  $('.photoset-grid').imagesLoaded( function() {
  				 $('.image-photo').removeAttr('height');
				 $('.image-photo').removeAttr('width');
				 photogrid();
				 $('.timeline').infinitescroll('resume');
				 
		  });
        }
      );
    });
    </script>
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

@extends('sbadminv2.frontend')
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
  
}
.intro-header .site-heading {
  padding: 100px 0 30px;
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
  font-size: 50px;
  text-shadow: 2px 2px 3px rgba(0,0,0,0.6);
  filter: alpha(opacity=60);
  
}


.transbox {
    margin: 20px;
	border-radius: 10px;
	color: #ffffff;
    background-color: #000000;
    border: 0px;
    background: rgba(0,0,0,0.6);
	filter: alpha(opacity=60);
}

.navbar-default {
    border-color: rgba(34,34,34,.05);
    font-family: 'Open Sans','Helvetica Neue',Arial,sans-serif;
    background: rgba(0,0,0,0.7);
    -webkit-transition: all .35s;
    -moz-transition: all .35s;
    transition: all .35s;
	
}

.navbar-default .navbar-header .navbar-brand {
    text-transform: uppercase;
    font-family: 'Open Sans','Helvetica Neue',Arial,sans-serif;
    font-weight: 700;
    color: #FFFFFF;
	text-shadow: 2px 2px 3px rgba(0,0,0,0.6);
  	filter: alpha(opacity=60);
}

.navbar-default .navbar-header .navbar-brand:hover,
.navbar-default .navbar-header .navbar-brand:focus {
    color: #63a0cc;
}

.navbar-default .nav > li>a,
.navbar-default .nav>li>a:focus {
    text-transform: uppercase;
    font-size: 13px;
    font-weight: 700;
    color: #BBBBBB;
}

.navbar-default .nav > li>a:hover,
.navbar-default .nav>li>a:focus:hover {
    color: #63a0cc;
}

.navbar-default .nav > li.active>a,
.navbar-default .nav>li.active>a:focus {
    color: #63a0cc!important;
    background-color: transparent;
}

.navbar-default .nav > li.active>a:hover,
.navbar-default .nav>li.active>a:focus:hover {
    background-color: transparent;
}

.navbar-default .navbar-toggle:hover,
.navbar-default .navbar-toggle:focus {
	background: rgba(0,0,0,0.7);
}

.navbar-default .navbar-toggle {
	background: rgba(0,0,0,0.7);
}



@media(min-width:768px) {
    .navbar-default {
        border-color: rgba(255,255,255,.3);
        background-color: transparent;
    }

    .navbar-default .navbar-header .navbar-brand {
        color: rgba(255,255,255,.7);
    }

    .navbar-default .navbar-header .navbar-brand:hover,
    .navbar-default .navbar-header .navbar-brand:focus {
        color: #fff;
    }

    .navbar-default .nav > li>a,
    .navbar-default .nav>li>a:focus {
        color: rgba(255,255,255,.7);
    }

    .navbar-default .nav > li>a:hover,
    .navbar-default .nav>li>a:focus:hover {
        color: #fff;
    }

    .navbar-default.affix {
        border-color: rgba(34,34,34,.05);
        background: rgba(0,0,0,0.7);
    }

    .navbar-default.affix .navbar-header .navbar-brand {
        
        color: #fff;
    }

    .navbar-default.affix .navbar-header .navbar-brand:hover,
    .navbar-default.affix .navbar-header .navbar-brand:focus {
        color: #63a0cc;
    }

    .navbar-default.affix .nav > li>a,
    .navbar-default.affix .nav>li>a:focus {
        color: #fff;
    }

    .navbar-default.affix .nav > li>a:hover,
    .navbar-default.affix .nav>li>a:focus:hover {
        color: #63a0cc;
    }
}

.pace {
  -webkit-pointer-events: none;
  pointer-events: none;

  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}

.pace-inactive {
  display: none;
}

.pace .pace-progress {
  background: #ffffff;
  position: fixed;
  z-index: 2000;
  top: 0;
  right: 100%;
  width: 100%;
  height: 2px;
}
</style>	
    
    <link href="/bower_components/animate.css/animate.min.css" rel="stylesheet">
    <script src="/bower_components/jquery-easing/jquery.easing.min.js"></script>
    
	<!-- Infinite Scroll -->
    <link href="/bower_components/jquery-infinite-scroll/main.css" rel="stylesheet">
	<script src="/bower_components/jquery-infinite-scroll/jquery.infinitescroll.min.js"></script>
    
    <!-- Photogrid -->
    <link href="/bower_components/photoset-grid/css/main.css" rel="stylesheet">
	<script type="text/javascript" src="/bower_components/photoset-grid/jquery.photoset-grid.min.js"></script>
    
    <!-- Images Loaded -->
	<script type="text/javascript" src="/bower_components/imagesloaded/imagesloaded.pkgd.min.js"></script>
    
    <!-- PACE -->
    <script type="text/javascript" src="/bower_components/PACE/pace.min.js"></script>
    
 <!-- ################################################################### -->
    
   <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="/">{{ $setting->judul1 }}</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                         <a target="_blank" href="https://www.facebook.com/{{ $setting->facebook }}"><p class="fa fa-facebook-square"></p> Facebook</a>
                    </li>
                    <li>
                        <a target="_blank" href="https://www.twitter.com/{{ $setting->twitter }}"><p class="fa fa-twitter-square"></p> Twitter</a>
                    </li>
                    <li>
                        <a target="_blank" href="https://www.instagram.com/{{ $setting->instagram }}"><p class="fa fa-instagram"></p> Instagram</a>
                    </li>
                    <li>
                         <a target="_blank" href="https://www.github.com/{{ $setting->github }}"><p class="fa fa-github"></p> Github</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
    
<!-- ################################################################### -->

	<header class="intro-header" style="background-image: url('{{ $setting->header_url }}')">
        
                
                    <div class="site-heading">
                    <div class="transbox">
                        <h1 style="padding:5px;">{{ $setting->judul2 }}</h1>
<hr style="max-width:50px;border-color: #f05f40;border-width: 3px;">
<p style="margin-left:15px; margin-right:15px; margin-bottom:50px; padding:5px;">{{ $setting->deskripsi }}</p>
                    </div>
                    <a class="page-scroll" href="#section"><i class="fa fa-angle-down infinite animated fadeInDown" style="font-size: 50px; color:#FFFFFF; margin-top:30px"></i></a>
                    </div>
              
         
    </header>
 
<!-- ################################################################### -->

 <section id="section"  style="background-color:#e9f0f5; max-width:780px; margin:0 auto;">
 	<ul class="timeline">
	<?php
	$i = 1;
	$tanggal = "";
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
                	<div class="div-image-loading" style="text-align:center">
                		
					</div>
                    <div class="photoset-grid" style=" max-width:600px; visibility:hidden" data-layout="{{$result->layout}}">
                    @foreach($result->attachments as $attachment)
                    	
                    	<img class="image-photo" src="{{ str_replace('image/upload/','image/upload/c_fill,w_250/',$attachment->secure_url) }}" data-highres="{{ $attachment->secure_url }}" data-lightbox="image-{{$result->id}}">
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
    <script src="/bower_components/bootstrap/js/affix.js"></script>
	<script src="/bower_components/FitText.js/jquery.fittext.js"></script>
    <script>
    $('#mainNav').affix({
        offset: {
            top: 100
        }
    })
    </script>
	<a href="#0" class="cd-top">Top</a>
    <link href="/codyhouse/back-to-top/css/style.css" rel="stylesheet">
    <script src="/codyhouse/back-to-top/js/main.js"></script>
	<script>
	$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
	});
	</script>
    
  	<script>
    $(function(){
      	var $container = $('.timeline');
      	$container.infinitescroll({
        navSelector  : '.pagination',    // selector for the paged navigation
      	nextSelector : '.pagination a',  // selector for the NEXT link (to page 2)
        itemSelector : '.test',     		 // selector for all items you'll retrieve
        loading: {
          finishedMsg: "You've reached the end of time",
          img: '/bower_components/jquery-infinite-scroll/output_DTGK2a.gif',
		  msgText: "Loading the next set of photos..."
          }
        },
        function( newElements ) {
		  $('.div-image-loading').append('<img class="image-loading" src="progress.gif" width="250">');
		  $('.timeline').infinitescroll('pause');
		  $('.image-photo').attr('height','50');
		  $('.image-photo').attr('width','50');
		  $('.photoset-grid').imagesLoaded()
  		  	.done( function( instance ) {
     			 $('.image-photo').removeAttr('height');
				 $('.image-photo').removeAttr('width');
				 photogrid();
				 $('.timeline').infinitescroll('resume');
				 $('.image-loading').hide();
			 })
  		   .progress( function( instance ) {
     			 //Pace.restart();
			 })
  

		  /*
		  $('.photoset-grid').imagesLoaded( function() {
			  	 Pace.restart();
  				 $('.image-photo').removeAttr('height');
				 $('.image-photo').removeAttr('width');
				 photogrid();
				 $('.timeline').infinitescroll('resume');
		  });
		  */
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

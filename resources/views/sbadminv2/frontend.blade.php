<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
    
    <title>{{ $setting->title }}</title>
	
    <!-- jQuery -->
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    
    <!-- Bootstrap Core CSS -->
    <link href="/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Social Buttons CSS -->
    <link href="/bower_components/bootstrap-social/bootstrap-social.css" rel="stylesheet">
    
    <!-- MetisMenu CSS -->
    <link href="/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="/bower_components/startbootstrap-sb-admin-2/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/bower_components/startbootstrap-sb-admin-2/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
   

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<!-- Twitter Open Graph -->
    <meta name="twitter:card" content="photo" />
	<meta name="twitter:site" content="@budisteikul" />
	<meta name="twitter:creator" content="@budisteikul" />
	
    
    
	<!-- Facebook Open Graph -->
	<meta property="og:type"	content="website" />
    <meta property="og:url"	content="{{ $setting->url }}" />
	<meta property="og:title"	content="{{ $setting->title }}" />
	<meta property="fb:app_id" content="251775208502162" />
    <meta property="og:image"	content="{{ $setting->image }}" />
	<meta property="og:description"   content="{{ $setting->deskripsi }}" />

    
</head>

<body style="background-color:#e9f0f5">


	@yield('content')
	
    
    
    <!-- Bootstrap Core JavaScript -->
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/bower_components/startbootstrap-sb-admin-2/dist/js/sb-admin-2.js"></script>


</body>

</html>

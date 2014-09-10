@section('head-scripts')
	<!-- bootstrap 3.0.2 -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- font Awesome -->
	<!--        <link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />-->
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- Ionicons -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- Morris chart -->
<!--	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/morris/morris.css') }}" rel="stylesheet" type="text/css" />-->
	<!-- jvectormap -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/jvectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
	<!-- Date Picker -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />
	<!-- Daterange picker -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet" type="text/css" />
	<!-- bootstrap wysihtml5 - text editor -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

	<link href="{{ asset('packages/mascame/admin/themes/admin-lte-custom/style.css') }}" rel="stylesheet" type="text/css" />

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js') }}"></script>
	<![endif]-->

	<?php Event::fire('head-scripts');  ?>
@show


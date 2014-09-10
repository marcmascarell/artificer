<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Artificer</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        @section('head-scripts')
            @include($theme . '.partials.head-scripts')
        @show
    </head>
    <body class="skin-blue fixed">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
			@include($theme . '.partials.header')
        </header>

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    @section('sidebar')
                        @include($theme . '.partials.sidebar')
                    @show
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
					@include($theme . '.partials.content-header')
                </section>

                <!-- Main content -->
                <section class="content">
					<?php HTML::admin_notifications(Event::fire('admin.notifications')); ?>

					@yield('content')
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

		@include($theme . '.partials.bottom-scripts')

    </body>
</html>
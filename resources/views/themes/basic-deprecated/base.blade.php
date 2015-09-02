<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- for Google and other search engines -->
    <meta name="google" content="notranslate">
    <meta name="google-site-verification" content=""/>

    <title></title>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <meta name="author" content="Chocolate Studio"/>
    <meta name="copyright" content="Chocolate Studio"/>
    <meta name="application-name" content=""/>

    <!-- for Facebook -->
    <meta property="og:title" content=""/>
    <meta property="og:description" content=""/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content=""/>
    <meta property="og:site_name" content=""/>
    <meta property="og:image" content=""/>

    <!-- for Twitter -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:title" content=""/>
    <meta name="twitter:description" content=""/>
    <meta name="twitter:url" content=""/>
    <meta name="twitter:image" content=""/>

    <link rel="shortcut icon" href="/images/16x16.png" type="image/png"/>

    <!-- Styles -->
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('packages/mascame/artificer/style.css') }}">

    <!--    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>-->

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script>
        if (!window.jQuery) {
            document.write('<script src="/js/jquery.min.js"><\/script>');
        }
    </script>

    <!--    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>-->
    <script src="{{ asset('packages/mascame/artificer/restfulizer.js') }}"></script>

    @yield('assets')

    @foreach ($fields as $field)
        {{ $field->assets() }}
        @endforeach

                <!--	{{ HTML::style('packages/mascame/artificer/widgets/dropzone/css/basic.css') }}-->

        <!--    Todo: this must go into their widget -->
        <!--	<script>-->
        <!--		$(document).ready(function() {-->
        <!--			Dropzone.options.myAwesomeDropzone = { // The camelized version of the ID of the form element-->
        <!---->
        <!--				// The configuration we've talked about above-->
        <!--				autoProcessQueue: false,-->
        <!--				uploadMultiple: true,-->
        <!--				parallelUploads: 100,-->
        <!--				maxFiles: 100,-->
        <!---->
        <!--				// The setting up of the dropzone-->
        <!--				init: function() {-->
        <!--					var myDropzone = this;-->
        <!---->
        <!--					// First change the button to actually tell Dropzone to process the queue.-->
        <!--					this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {-->
        <!--						// Make sure that the form isn't actually being sent.-->
        <!--						e.preventDefault();-->
        <!--						e.stopPropagation();-->
        <!--						myDropzone.processQueue();-->
        <!--					});-->
        <!---->
        <!--					// Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead-->
        <!--					// of the sending event because uploadMultiple is set to true.-->
        <!--					this.on("sendingmultiple", function() {-->
        <!--						// Gets triggered when the form is actually being sent.-->
        <!--						// Hide the success button or the complete form.-->
        <!--					});-->
        <!--					this.on("successmultiple", function(files, response) {-->
        <!--						// Gets triggered when the files have successfully been sent.-->
        <!--						// Redirect user or notify of success.-->
        <!--					});-->
        <!--					this.on("errormultiple", function(files, response) {-->
        <!--						// Gets triggered when there was an error sending the files.-->
        <!--						// Maybe show form again, and notify user of error-->
        <!--					});-->
        <!--				}-->
        <!---->
        <!--			}-->
        <!--		});-->
        <!--	</script>-->



        <script type="text/javascript">
            //        $(document).ready(function(){
            ////            var choose = $('[data-box]').focalpoint({
            ////                point: $('[data-point]'),
            ////                callback: function(pos){
            ////                    $('[data-position]').html(JSON.stringify(pos));
            ////                }
            ////            });
            //
            //        });
        </script>
</head>
<body>

<header class="container-fluid">
    <div class="row">

    </div>

    <h1 class="col-md-2">Admin</h1>

    <div class="col-md-8">
        <!--            <ul>-->
        <!--                @foreach ($models as $m)-->
        <!--                    <li>-->
        <!--                        <a href="{{ route('admin.model.all', array('slug' => $m['route'])) }}">-->
        <!--                            @if (isset($m['options']['title']))-->
        <!--                                {{ $m['options']['title'] }}-->
        <!--                            @else-->
<!--                                {{ $m['table'] }}-->
        <!--                            @endif-->
<!--                        </a>-->
        <!--                    </li>-->
        <!--                @endforeach-->
<!--            </ul>-->
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-md-3 col-lg-2">
            <nav class="lateral">
                <ul>
                    @foreach ($menu as $m)
                        <li>
								<span class="icon">
									{{ $m['icon'] }}
								</span>
                            {{ HTML::link($m['link'], $m['title']) }}
                        </li>
                    @endforeach

                    @foreach ($models as $m)
                        <li>
                            <a href="{{ route('admin.model.all', array('slug' => $m['route'])) }}">
                                @if (isset($m['options']['title']))
                                    {{ $m['options']['title'] }}
                                @else
                                    {{ $m['table'] }}
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="col-sm-8 col-md-9 col-lg-10">
            @yield('content')
        </div>
    </div>
</div>

</body>
</html>

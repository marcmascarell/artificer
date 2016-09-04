<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>

    {!! \Mascame\Artificer\Artificer::assetManager()->css() !!}
    {!! \Mascame\Artificer\Artificer::assetManager()->js() !!}

    <style>
        body {
            display: flex;
            justify-content: center;
            align-content: center;
            font-size: 15px;
            margin: 20px;
        }

        .container {
            flex: 0 1 100%;
            margin: 0;
            align-self: center;
        }

        .steps {
            margin-top: 15px;
        }

        .panel-title {
            display: flex;
            align-content: space-between;
        }

        .panel-title div {
            flex-grow: 0;
            flex-shrink: 1;
            margin: 0;
        }

        .panel-title div.step-title {
            flex-grow: 1;
            flex-shrink: 1;
            text-align: center;
        }

        .panel-title div.step-title i {
            margin-right: 5px;
            color: #7a7a7a;
            /*font-size: 12px;*/
        }

        .panel-title div.step-status i {
            /*color: #7a7a7a;*/
            font-size: 12px;
        }

        .panel-title div.step-number {
            font-size: 12px;
            color: #7a7a7a;
        }

        .invisible {
            visibility: hidden;
        }

        .label-default {
            background-color: #f1f1f1;
            color: #636262;
            border: 1px solid #dad8d8;
            font-weight: normal;
            font-size: 12px;
        }

        li:not(:first-child) {
            margin-top: 10px;
        }
    </style>

    <script>
        $(function () {
            var $form = $('form'),
                installationCompleted = false;

            /**
             * Yes, it is a bit fake just to be fancy ;p
             *
             * @param $step
             */
            function markAsWorkingOn($step) {
                markAsLoading($step);

                setTimeout(function() {
                    $('.step-status i').addClass('invisible');

                    $step.addClass('panel-success');

                    var $next = getNextStep();

                    if ($next.length > 0) {
                        markAsWorkingOn($next);
                    } else {
                        setTimeout(onInstallationCompleted, 1000);
                    }
                }, 1500);
            }

            function markAsLoading($step) {
                $step.find('.step-status i').removeClass('invisible');
            }

            function getNextStep() {
                return $('.step:not(.panel-success)').first();
            }

            /**
             * Triggered when "animation" ends
             */
            function onInstallationCompleted() {
                if (installationCompleted) {
                    window.location = $form.data('after');
                } else {
                    alert("I was unable to install by myself :'(");
                }
            }

            $form.on('submit', function(e) {
                e.preventDefault();

                $form.find('button').prop( "disabled", true);

                markAsLoading(getNextStep());

                $.post($form.attr('action'), {'_token': $form.find('[name="_token"]').val()}, function(data) {
                    markAsWorkingOn(getNextStep());

                    if (data && data.installed) {
                        installationCompleted = true;
                    }
                });
            })
        });
    </script>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="text-center">
                <img src="{{ asset('packages/mascame/admin/img/logo.png') }}" alt="" height="200">

                <h1>Hi, I'm Artificer.</h1>
                <h2>I will be glad to serve you!</h2>
            </div>
        </div>

        <div class="row steps">
            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                <p>Lets get to work!</p>

                <p>Here you got a detailed resume of what I will do, <b>once you are fine press the "Start" button</b>.</p>

                <br>
                @foreach($steps as $step)
                    <div class="panel panel-default step" data-action="">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <div class="step-number">Step {{ $loop->index + 1 }}</div>
                                <div class="step-title">
                                    <i class="{{ $step['icon'] }}"></i>
                                    {{ $step['title'] }}
                                </div>
                                <div class="step-status">
                                    <i class="invisible fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
                                </div>
                            </h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                @foreach($step['actions'] as $action)
                                    <li>{!! $action !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <form class="col-md-12 text-center" method="POST" action="{{ route('admin.install') }}" data-after="{{ route('admin.home') }}">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary btn-lg">START</button>
            </form>
        </div>
    </div>
</body>
</html>
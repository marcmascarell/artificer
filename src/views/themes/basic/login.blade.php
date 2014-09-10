<!doctype html>
<html>
<head>
    <link rel="shortcut icon" href="/assets/img/favicon.ico" />
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Varela+Round">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">

    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title>Admin login</title>

    <style>
        @charset "utf-8";
        /* CSS Document */

        /* ---------- FONTAWESOME ---------- */
        /* ---------- http://fortawesome.github.com/Font-Awesome/ ---------- */
        /* ---------- http://weloveiconfonts.com/ ---------- */

        @import url(http://weloveiconfonts.com/api/?family=fontawesome);

        /* ---------- ERIC MEYER'S RESET CSS ---------- */
        /* ---------- http://meyerweb.com/eric/tools/css/reset/ ---------- */

        @import url(http://meyerweb.com/eric/tools/css/reset/reset.css);

        /* ---------- FONTAWESOME ---------- */

        [class*="fontawesome-"]:before {
            font-family: 'FontAwesome', sans-serif;
        }

        /* ---------- GENERAL ---------- */

        body {
            background-color: #C0C0C0;
            color: #000;
            font-family: "Varela Round", Arial, Helvetica, sans-serif;
            font-size: 16px;
            line-height: 1.5em;
        }

        input {
            border: none;
            font-family: inherit;
            font-size: inherit;
            font-weight: inherit;
            line-height: inherit;
            -webkit-appearance: none;
        }

        /* ---------- LOGIN ---------- */

        .errors {
            color: #ff0000;
        }

        #login {
            margin: 50px auto;
            width: 400px;
        }

        #login h2 {
            background-color: #444b42;
            -webkit-border-radius: 20px 20px 0 0;
            -moz-border-radius: 20px 20px 0 0;
            border-radius: 20px 20px 0 0;
            color: #fff;
            font-size: 28px;
            padding: 20px 26px;
        }

        #login h2 span[class*="fontawesome-"] {
            margin-right: 14px;
        }

        #login fieldset {
            background-color: #fff;
            -webkit-border-radius: 0 0 20px 20px;
            -moz-border-radius: 0 0 20px 20px;
            border-radius: 0 0 20px 20px;
            padding: 20px 26px;
        }

        #login fieldset p {
            color: #777;
            margin-bottom: 14px;
        }

        #login fieldset p:last-child {
            margin-bottom: 0;
        }

        #login fieldset input {
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
        }

        #login fieldset input[type="text"], #login fieldset input[type="email"], #login fieldset input[type="password"] {
            background-color: #eee;
            color: #777;
            padding: 4px 10px;
            width: 328px;
        }

        #login fieldset input[type="submit"] {
            background-color: #33cc77;
            color: #fff;
            display: block;
            margin: 0 auto;
            padding: 4px 0;
            width: 100px;
        }

        #login fieldset input[type="submit"]:hover {
            background-color: #28ad63;
        }
    </style>
</head>
<body>



<div id="login">

    <h2><span class="fontawesome-lock"></span>Login</h2>

    {{ Form::open(array('url' => URL::route("admin.login"))) }}

        <fieldset>

<!--            <img class="img-responsive" src="/assets/img/" alt="Logo" />-->
            <hr>

            <ul class="errors">
                <li>
                    {{ $errors->first('email') }}
                </li>
                <li>
                    {{ $errors->first('password') }}
                </li>
            </ul>

            <p>
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', Input::old('email')) }}
            </p>

            <p>
                {{ Form::label('password', 'Password') }}
                {{ Form::password('password') }}
            </p>


            <p>{{ Form::submit('Enviar!') }}</p>


        </fieldset>

    {{ Form::close() }}

</div> <!-- end login -->
<!-- if there are login errors, show them here -->

</body>
</html>

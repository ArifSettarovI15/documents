<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOGIN</title>
    <script src="https://kit.fontawesome.com/6d6e036f3b.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


    <style>
        /* Demo Background */
        html, body {
            height: 100%;
            background: #edf2f7;
        }

        /* Form Style */
        .form-horizontal {
            background: #fff;
            /*padding-bottom: 40px;*/
            border-radius: 15px;
            text-align: center;
            box-shadow: 18px 18px 79px -17px rgba(38, 36, 38, 0.76);

        }

        .form-horizontal .heading {
            display: block;
            font-size: 35px;
            font-weight: 700;
            padding: 35px 0;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 30px;
        }

        .form-horizontal .form-group {
            padding: 0 40px;
            margin: 0 0 25px 0;
            position: relative;
        }

        .form-horizontal .form-control {
            background: #f0f0f0;
            border: none;
            border-radius: 20px;

            padding: 0 20px 0 45px;
            height: 40px;
            transition: all 0.3s ease 0s;


        }

        .form-horizontal .form-control:focus {
            background: #e0e0e0;
            box-shadow: none;
            outline: 0 none;
        }

        .form-horizontal .form-group i {
            position: absolute;
            top: 12px;
            left: 60px;
            font-size: 17px;
            color: #c8c8c8;
            transition: all 0.5s ease 0s;
        }

        .form-horizontal .form-control:focus + i {
            color: #00b4ef;
        }

        .form-horizontal .fa-question-circle {
            display: inline-block;
            position: absolute;
            top: 12px;
            right: 60px;
            font-size: 20px;
            color: #808080;
            transition: all 0.5s ease 0s;
        }

        .form-horizontal .fa-question-circle:hover {
            color: #000;
        }

        .form-horizontal .main-checkbox {
            float: left;
            width: 20px;
            height: 20px;
            background: #11a3fc;
            border-radius: 50%;
            position: relative;
            margin: 5px 0 0 5px;
            border: 1px solid #11a3fc;
        }

        .form-horizontal .main-checkbox label {
            width: 20px;
            height: 20px;
            position: absolute;
            top: 0;
            left: 0;
            cursor: pointer;
        }

        .form-horizontal .main-checkbox label:after {
            content: "";
            width: 10px;
            height: 5px;
            position: absolute;
            top: 5px;
            left: 4px;
            border: 3px solid #fff;
            border-top: none;
            border-right: none;
            background: transparent;
            opacity: 0;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
        }

        .form-horizontal .main-checkbox input[type=checkbox] {
            visibility: hidden;
        }

        .form-horizontal .main-checkbox input[type=checkbox]:checked + label:after {
            opacity: 1;
        }

        .form-horizontal .text {
            float: left;
            margin-left: 7px;
            line-height: 20px;
            padding-top: 5px;
            text-transform: capitalize;
        }

        .form-horizontal .btn {
            font-size: 14px;
            color: #fff;
            background: #00b4ef;
            border-radius: 30px;
            padding: 10px 25px;
            border: none;
            text-transform: capitalize;
            transition: all 0.5s ease 0s;
        }

        .last_inputs {
            height: 40px;
        }
        .btn-right{
            float: right;
        }

        @media only screen and (max-width: 479px) {
            .form-horizontal .form-group {
                padding: 0 25px;
            }

            .form-horizontal .form-group i {
                left: 45px;
            }

            .form-horizontal .btn {
                padding: 10px 20px;
            }
        }


    </style>

    <link rel="stylesheet" href="http://tigerweb.eng/assets/User/styles.css">
</head>
<body>

<div class="container h-100 d-flex justify-content-center">
    <div class="jumbotron my-auto" style="margin: 0;padding: 0; background: none;">
        <div class="col-md-offset-3 col-md-6" style="padding-top: 10px;padding-bottom: 10px;">
            <form class="form-horizontal" action="{{route('user.login')}}"
                  style="padding: 20px; width: 470px;margin: 10px;" method="POST">

                <span class="heading">АВТОРИЗАЦИЯ</span>
                <div class="form-group">
                    <input type="text" class="form-control" id="inputEmail" name="login" placeholder="Логин или email">
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group help">
                    <input type="password" class="form-control" id="inputPassword" name="password"
                           placeholder="Пароль">
                    <i class="fa fa-lock"></i>
                    {{--                    <a href="#" class="fa fa-question-circle"></a>--}}
                </div>
                <div class="form-group last_inputs">
                    <div class="main-checkbox">
                        <input type="checkbox" value="none" id="checkbox1" name="remember"/>
                        <label for="checkbox1"></label>
                    </div>
                    <span class="text">Запомнить</span>
                    <button type="submit" class="btn btn-default btn-right">ВХОД</button>
                </div>
                <div class="form-group last_inputs">
                    <span class="text">
                        <a class="alert-link" href="{{route('user.forgot')}}" style="color: #718096;">Забыли пароль?</a>
                    </span>
                    @if(Route::has('user.register'))
                        <span class="text" style="float: right">
                            <a class="alert-link" href="{{route('user.register')}}">Регистрация</a>
                        </span>
                    @endif
                </div>
                @if(Route::has('user.social'))
                    <div>
                        <p>или войдите через:</p>
                        <a href="{{ route('user.social', 'vkontakte') }}" type="button" class="btn btn-primary mx-1">
                            <i class="fab fa-vk"></i>
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('public/js/app.js') }}"></script>
<div class="message-box" style="position: absolute; top: 10px; right: 10px;"></div>
</body>
</html>

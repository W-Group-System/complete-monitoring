<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login 2</title>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row" style="display: flex; justify-content: center; align-items: center;">

            <div class="col-md-6">
                <img src="{{URL::asset('/img/bg.png')}}" style='width:100%;'>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form method="POST" action="{{ route('login') }}"  aria-label="{{ __('Login') }}" onsubmit='show()'>
                        @csrf
                        <div class="form-group">
                            <input type="email" name='email' value="{{ old('email') }}" class="form-control" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" name='password' class="form-control" placeholder="******" required="">
                        </div>
                        @if($errors->any())
                            <div class="form-group alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <strong>{{$errors->first()}}</strong>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                 W Group Inc. © 2025
            </div>
        </div>
    </div>

</body>

</html>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('default.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('default.png')}}" type="image/x-icon">
    <title>near-me</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/chartist.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/prism.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{asset('cp/endless/assets/css/light-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('cp/endless/assets/css/responsive.css')}}">
    <style>
        .auth-bg {
         background:none;
        }
        body{
            background-color: gray;
        }
    </style>
</head>
<body class="" dir="rtl">
<!-- Loader starts-->
{{--<div class="loader-wrapper">
    <div class="loader bg-white">
        <div class="whirly-loader"> </div>
    </div>
</div>--}}
<!-- Loader ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper">
    <div class="auth-bg">
        <div class="authentication-box">
            <div class="text-center"><img src="{{asset('default.png')}}" height="80" width="80" alt=""></div>
            <div class="card mt-4">
                <div class="card-body">
                    <div class="text-center">
                        <h4>تسجيل الدخول</h4>
                        <h6>قم بادخال بريدك الالكتروني وكلمة المرور </h6>
                    </div>
                    <div class="text-center" style="color: red;"><strong>{{Session::get('error')}}</strong></div>
                    <form action="{{route('login')}}" method="post" class="theme-form">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label class="col-form-label pt-0" style="float:right">البريد الالكتروني</label>
                            <input class="form-control" type="text" required="" name="email">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" style="float:right">كلمة المرور</label>
                            <input class="form-control" type="password" required="" name="password">
                        </div>
                        <div class="mg-t-60 tx-center">
                            <a target="_blank" href="http://2grand.net/">
                                <img src="{{asset('grand.png')}}" style="max-width: 20% !important;display: table;margin: 0 auto;">
                            </a>
                        </div>
                        <div class="form-group form-row mt-3 mb-0">
                            <button class="btn btn-primary btn-block" type="submit">تسجيل الدخول</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- jQuery 2.1.4 -->
<script src="{{URL::to('public/admin/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>
<!-- Bootstrap 3.3.4 -->
<script src="{{URL::to('public/admin/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{URL::to('public/admin/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>

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
    <![endif]-->
    <style>
        @font-face {
            font-family:GESSTwoBold ;
            src: url({{asset('public/GE-SS-Two-Bold.otf')}});
        }
        * {
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
            font-family: GESSTwoBold, Arial, sans-serif;
            /*color: #602248;*/
        }
    </style>
</head>
<body onload="window.print();" dir="rtl">
<div id="DivIdToPrint" class="invoice-box invoice">
    <div class="box-header " >
        <center class="center">
            <h2> تقرير رقم  {{ time() }}</h2>
        </center>

        <h3 class="box-title pull-right" >
            <br>
            <b> المناديب</b> &nbsp;&nbsp;( {{$usercount}} )&nbsp;&nbsp;
        </h3>

        <span class="pull-left" >
            <img src="{{asset("default.png")}}" style="width:80px; height:60px">
        </span>


    </div>
    <div class="clearfix"></div>
    <br>

    <!-- /.box-header -->
    <div class="box-body invoice-info" >
        <table id="example2" class="table table-bordered " dir="rtl">
            <thead>

            <tr>
                <th scope="col">#</th>
                <th scope="col"> الاسم </th>
                <th scope="col"> الصورة </th>
                <th scope="col">الموبايل </th>
                <th scope="col"> البريد الالكتروني</th>
                <th scope="col"> لون السيارة </th>
                <th scope="col"> رقم السيارة </th>
                <th scope="col"> مستوي السيارة  </th>
                <th scope="col"> الحالة </th>
            </tr>
            </thead>
            <tbody id="myTable">
            @foreach($users as $c)
                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="{{$c->suspend == 1 ? 'table-danger' :''}}">
                    <td>{{$c->id}}</td>
                    <td>{{$c->f_name}} {{$c->l_name}}</td>
                    @if($c->image != NULL)
                        <th><img src="{{$c->image}}"  width="40px" height="40px"></th>
                    @else
                        <th> - </th>
                    @endif
                    <td>{{$c->phone}}</td>
                    <td>{{$c->email}}</td>
                    <td>
                        <input class="form-control" type="color" value="{{$c->car_color}}" data-original-title="" title="" disabled>
                    </td>
                    <td>{{$c->car_num}}</td>
                    <td>{{$c->level}}</td>

                    <td>
                        @if($c->active == 1)
                            <i class="font-success show icon-check"></i>
                        @else
                            <i class="font-danger show icon-close"></i>
                        @endif
                    </td>
                </tr>
            @endforeach

            </tbody>

        </table>
    </div>
    <!-- /.box-body -->
</div>

<!-- AdminLTE App -->
<script src="{{asset('public/admin/dist/js/app.min.js')}}"></script>
</body>
</html>

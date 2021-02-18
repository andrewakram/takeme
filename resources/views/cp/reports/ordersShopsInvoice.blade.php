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
            <b> طلبات المتاجر</b> &nbsp;&nbsp;( {{$usercount}} )&nbsp;&nbsp;
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
                                <th scope="col">الحالة</th>
                                <th scope="col">العميل</th>
                                <th scope="col">المندوب</th>
                                <th scope="col"> موقع البدء</th>
                                <th scope="col"> موقع الانهاء</th>
                                <th scope="col">وقت التوصيل</th>
                                <th scope="col"> تكلفة الطلب</th>
                                <th scope="col"> تكلفة التوصيل</th>
                                <th scope="col"> تاريخ الطلب</th>
            </tr>
            </thead>
            <tbody id="myTable">
            @foreach($users as $c)
                <tr>
                    <td>{{$c->id}}</td>
                                    <td style="text-align: right">
                                        @if(isset($c->order_status) && $c->order_status !=null)

                                            <span class="badge badge-primary">القبول:</span>
                                            {{$c->order_status->accept == null ? '-' : $c->order_status->accept}}
                                            <br>

                                            <span class="badge badge-dark"> استلام المندوب:</span>
                                            {{$c->order_status->received == null ? '-' : $c->order_status->received}}
                                            <br>

                                            <span class="badge badge-warning">في الطريق:</span>
                                            {{$c->order_status->on_way == null ? '-' : $c->order_status->on_way}}
                                            <br>

                                            <span class="badge badge-success">انتهاء:</span>
                                            {{$c->order_status->finished == null ? '-' : $c->order_status->finished}}
                                            <br>

                                            <span class="badge badge-danger">الغاء:</span>
                                            {{$c->order_status->cancelled == null ? '-' : $c->order_status->cancelled}}
                                            <br>

                                            @if($c->order_status->cancel_by != null)
                                                @if($c->order_status->cancel_by === 0)
                                                    <b class="badge badge-danger">ملغي بواسطة العميل</b>
                                                @elseif($c->order_status->cancel_by === 1)
                                                    <b class="badge badge-danger">ملغي بواسطة المندوب</b>
                                                @endif
                                            @endif
                                        @endif
                                    </td>
                                    @if(($c->user) != null)
                                        <td>
                                            {{$c->user->id}}
                                            <br>
                                            {{$c->user->name}}
                                            <br>
                                            {{$c->user->phone}}
                                            <br>
                                            {{$c->user->email}}
                                            <br>
                                            <img src="{{$c->user->image}}" width="50px" height="50px">
                                        </td>
                                    @else
                                        <td>
                                            -
                                        </td>
                                    @endif
                                    @if(($c->delegate) != null)
                                        <td>
                                            {{$c->delegate->id}}
                                            <br>
                                            {{$c->delegate->f_name}} {{$c->delegate->l_name}}
                                            <br>
                                            {{$c->delegate->phone}}
                                            <br>
                                            {{$c->delegate->email}}
                                            <br>
                                            <img src="{{$c->delegate->image}}" width="50px" height="50px">
                                        </td>
                                    @else
                                        <td>
                                            -
                                        </td>
                                    @endif
                                    
                                    <td>
                                        <a target="_blank"
                                           href="https://www.google.com/maps/search/?api=1&query={{$c->in_lat}},{{$c->in_lng}}">
                                            <i class="icon-location-pin"></i>
                                            <br>
                                            {{$c->in_address}}<br>
                                            {{$c->in_city_name}}
                                        </a>

                                    </td>
                                    <td>
                                        <a target="_blank"
                                           href="https://www.google.com/maps/search/?api=1&query={{$c->out_lat}},{{$c->out_lng}}">
                                            <i class="icon-location-pin"></i>
                                            <br>
                                            {{$c->end_address}}<br>
                                            {{$c->out_city_name}}
                                        </a>

                                    </td>

                                    <td>{{$c->delivery_time}}</td>
                                    <td ><span class="badge badge-dark">{{$c->total_cost}}</span></td>
                                    <td >
                                        <span class="badge badge-success">
                                            {{isset($c->offer->offer) ? $c->offer->offer : "-"}}
                                        </span>
                                        {{isset($c->country->currency) ? $c->country->currency : "-"}}
                                    </td>
                                    <td >{{$c->created_at}}</td>
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

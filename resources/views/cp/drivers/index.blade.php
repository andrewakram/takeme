@extends('cp.index')
@section('imgStylHedr')
    <style>
        /*Eliminates padding, centers the thumbnail */

        body, html {
            padding: 0;
            margin: 0;
            text-align: center;
        }

        /* Styles the thumbnail */

        a.lightbox img {
            height: 150px;
            border: 3px solid white;
            box-shadow: 0px 0px 8px rgba(0,0,0,.3);
            margin: 94px 20px 20px 20px;
        }

        /* Styles the lightbox, removes it from sight and adds the fade-in transition */

        .lightbox-target {
            position: fixed;
            top: -100%;
            width: 100%;
            background: rgba(0,0,0,.7);
            opacity: 0;
            -webkit-transition: opacity .5s ease-in-out;
            -moz-transition: opacity .5s ease-in-out;
            -o-transition: opacity .5s ease-in-out;
            transition: opacity .5s ease-in-out;
            overflow: hidden;
        }

        /* Styles the lightbox image, centers it vertically and horizontally, adds the zoom-in transition and makes it responsive using a combination of margin and absolute positioning */

        .lightbox-target img {
            margin: auto;
            position: absolute;
            /*top: 150px;
            left:150px;*/
            right:0;
            bottom: 0;
            max-height: 70%;
            max-width: 70%;
            border: 3px solid white;
            box-shadow: 0px 0px 8px rgba(0,0,0,.3);
            box-sizing: border-box;
            -webkit-transition: .5s ease-in-out;
            -moz-transition: .5s ease-in-out;
            -o-transition: .5s ease-in-out;
            transition: .5s ease-in-out;
        }

        /* Styles the close link, adds the slide down transition */

        a.lightbox-close {
            display: block;
            width:50px;
            height:50px;
            box-sizing: border-box;
            background: white;
            color: black;
            text-decoration: none;
            position: absolute;
            top: -80px;
            right: 0;
            -webkit-transition: .5s ease-in-out;
            -moz-transition: .5s ease-in-out;
            -o-transition: .5s ease-in-out;
            transition: .5s ease-in-out;
        }

        /* Provides part of the "X" to eliminate an image from the close link */

        a.lightbox-close:before {
            content: "";
            display: block;
            height: 30px;
            width: 1px;
            background: black;
            position: absolute;
            left: 26px;
            top:10px;
            -webkit-transform:rotate(45deg);
            -moz-transform:rotate(45deg);
            -o-transform:rotate(45deg);
            transform:rotate(45deg);
        }

        /* Provides part of the "X" to eliminate an image from the close link */

        a.lightbox-close:after {
            content: "";
            display: block;
            height: 30px;
            width: 1px;
            background: black;
            position: absolute;
            left: 26px;
            top:10px;
            -webkit-transform:rotate(-45deg);
            -moz-transform:rotate(-45deg);
            -o-transform:rotate(-45deg);
            transform:rotate(-45deg);
        }

        /* Uses the :target pseudo-class to perform the animations upon clicking the .lightbox-target anchor */

        .lightbox-target:target {
            opacity: 1;
            top: 80px;
            bottom: 0;
        }

        .lightbox-target:target img {
            max-height: 100%;
            max-width: 100%;
        }

        .lightbox-target:target a.lightbox-close {
            top: 0px;
        }
    </style>
@endsection
@section('content')
    <div class="page-body" dir="rtl">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-right">


                            @include('cp.layouts.messages')


                            <h3>
                                <i data-feather="home"></i>
                                السائقين
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المتاجر</li>
                            </ol>--}}
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-lg-12 col-xl-12">
                    <div class="table-responsive">
                        <table class="table" id="myTable" style="height: 400px;display: block; overflow-y: scroll;">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"> الحالة </th>
                                <th scope="col"> الدولة </th>
                                <th scope="col"> الاسم </th>
                                <th scope="col"> الصورة </th>
                                <th scope="col">الموبايل </th>
                                <th scope="col"> البريد الالكتروني</th>
                                <th scope="col"> مواصفات السيارة </th>
                                <th scope="col"> صورة السيارة(امامي)</th>
                                <th scope="col"> صورة السيارة(خلفي)</th>
                                <th scope="col"> صورة التأمين </th>
                                <th scope="col"> صورة الرخصة </th>
                                <th scope="col"> صورة القيادة المدنية</th>

                                {{--<th scope="col">الاختيارات</th>--}}
                            </tr>
                            </thead>
                            <tbody >
                            @foreach($users as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="{{$c->suspend == 1 ? 'table-danger' :''}}">
                                    <td>
                                        {{$c->id}}
                                        @if($c->accept == 0)
                                            <a href="{{route('acceptDriver',$c->id)}}" >
                                                <button title="قبول " class="btn btn-success">
                                                    قبول
                                                </button>
                                            </a>
                                        @endif

                                        @if($c->suspend == 0)
                                            <a href="{{route('editClientStatus',$c->id)}}" >
                                                <button title="ايقاف " class="btn btn-danger">
                                                    <i class="fa fa-minus-circle"></i>
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{route('editClientStatus',$c->id)}}" >
                                                <button title="اعادة تشغيل " class="btn btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </a>
                                        @endif

                                        @if($c->driver_trips_count($c->id) > 0)
                                            <a href="{{asset('admin/driver-trips/'.$c->id)}}" >
                                                <button title="عرض الرحلات " class="btn btn-success">
                                                    عرض الرحلات
                                                </button>
                                            </a>
                                        @endif
                                    </td>

                                    <td>
                                        @if($c->active == 1)
                                            <i class="font-success show icon-check"></i>
                                        @else
                                            <i class="font-danger show icon-close"></i>
                                        @endif
                                    </td>

                                    <td>{{$c->country->name}}</td>
                                    <td>{{$c->f_name}} {{$c->l_name}}</td>

                                    <td>
                                        @if(isset($c->image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==image==--}}
                                            <div class="modal fade" id="image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>



                                    <td>
                                        {{$c->phone}}<br><br>

                                        <button title=" المستويات المشترك بها السائق" type="button" class="btn btn-warning" data-toggle="modal" data-target="#show_{{$c->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye" color="white" data-toggle="modal">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                    </td>
                                    <td>
                                        <span>{{$c->email}}</span> <br><br>

                                        <button title="تعديل مستوي السيارة" type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            {{$c->car_levell->name}}
                                        </button>
                                    </td>
                                    <td>
                                        {{$c->color_name}}
                                        <div style="width:60px; height:20px;background-color:{{$c->car_color}} ;border-radius: 5px;color:white;">
                                            لون السيارة
                                        </div>
{{--                                        <input class="form-control" type="color" value="{{$c->car_color}}" data-original-title="" title="" disabled>--}}
                                        {{$c->car_num}} <br>
                                        {{$c->car_model}}
                                    </td>

                                    <td>
                                        @if(isset($c->driver_documents->front_car_image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#front_car_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->driver_documents->front_car_image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==front_car_image==--}}
                                            <div class="modal fade" id="front_car_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->driver_documents->front_car_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($c->driver_documents->back_car_image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#back_car_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->driver_documents->back_car_image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==back_car_image==--}}
                                            <div class="modal fade" id="back_car_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->driver_documents->back_car_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>


                                    <td>
                                        @if(isset($c->driver_documents->insurance_image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#insurance_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->driver_documents->insurance_image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==insurance_image==--}}
                                            <div class="modal fade" id="insurance_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->driver_documents->insurance_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if(isset($c->driver_documents->license_image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#license_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->driver_documents->license_image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==license_image==--}}
                                            <div class="modal fade" id="license_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->driver_documents->license_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if(isset($c->driver_documents->civil_image))
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#civil_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->driver_documents->civil_image}}" width="50px" height="50px"></img>
                                            </button>
                                            {{--==civil_image==--}}
                                            <div class="modal fade" id="civil_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->driver_documents->civil_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>


                                    <div class="modal fade" id="show_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">قائمة السيارات المشترك بها السائق</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>

                                                    <div class="modal-body" style="text-align: center">
                                                        @if(sizeof($c->driver_car_levels) > 0)
                                                            @foreach($c->driver_car_levels as $key=> $driver_car_level)
                                                                <h3 style="text-align: right">
                                                                    <b class="badge badge-dark">
                                                                        {{$key+1}} - {{$driver_car_level->car_level_name}}
                                                                    </b>
                                                                </h3>
                                                            @endforeach
                                                        @else
                                                            <h3>لا يوجد</h3>
                                                        @endif

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">اغلاق</button>

                                                    </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل مستوي السيارة</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editCarLevel')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="user_id" value="{{$c->id}}">

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد مستوي السيارة</label>
                                                            <div class="col-lg-12">
                                                                <select name="car_level" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                    @foreach($carLevels as $carLevel)
                                                                        <option value="{{$carLevel->id}}" {{$carLevel->id == $c->car_level_id ? "selected" : ""}} >{{$carLevel->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">اغلاق</button>
                                                        <button class="btn btn-primary" type="submit">تعديل</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$users->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>




@endsection

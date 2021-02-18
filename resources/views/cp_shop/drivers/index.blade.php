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
                                <th scope="col"> رخصة القيادة </th>
                                <th scope="col"> صورة البطاقة (امامي)  </th>
                                <th scope="col"> صورة البطاقة (خلفي)  </th>
                                <th scope="col"> رخصة السيارة (امامي)  </th>
                                <th scope="col"> رخصة السيارة (خلفي)  </th>
                                <th scope="col"> صورة السيارة  </th>

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

                                    <td>{{$c->name_ar}} / {{$c->name_en}}</td>
                                    <td>{{$c->name}}</td>

                                    

                                    <td>{{$c->phone}}</td>
                                    <td>
                                        <span>{{$c->email}}</span> <br><br>

                                        <button title="تعديل مستوي السيارة" type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            {{$c->car_level_name}}
                                        </button>
                                    </td>
                                    <td>
                                        {{$c->color_name}}
                                        <input class="form-control" type="color" value="{{$c->car_color}}" data-original-title="" title="" disabled>
                                        {{$c->car_num}} <br>
                                        {{$c->car_model}}
                                    </td>

                                    <td>
                                        <a class="lightbox" href="#{{$c->driving_license}}"  >
                                            <img src="{{asset('/captins/licenses/'.$c->driving_license)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->driving_license}}">
                                            <img src="{{asset('/captins/licenses/'.$c->driving_license)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->driving_license !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#driving_license{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==driving_license==--}}
                                    <div class="modal fade" id="driving_license{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/licenses/'.$c->driving_license)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==driving_license==--}}

                                    <td>
                                        <a class="lightbox" href="#{{$c->id_image_1}}"  >
                                            <img src="{{asset('/captins/id_images_1/'.$c->id_image_1)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->id_image_1}}">
                                            <img src="{{asset('/captins/id_images_1/'.$c->id_image_1)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->id_image_1 !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#id_images_1{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==id_images_1==--}}
                                    <div class="modal fade" id="id_images_1{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/id_images_1/'.$c->id_image_1)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==id_images_1==--}}

                                    <td>
                                        <a class="lightbox" href="#{{$c->id_image_2}}"  >
                                            <img src="{{asset('/captins/id_images_2/'.$c->id_image_2)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->id_image_2}}">
                                            <img src="{{asset('/captins/id_images_2/'.$c->id_image_2)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->id_image_2 !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#id_images_2{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==id_images_2==--}}
                                    <div class="modal fade" id="id_images_2{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/id_images_2/'.$c->id_image_2)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==id_images_2==--}}

                                    <td>
                                        <a class="lightbox" href="#{{$c->car_licenses_1}}"  >
                                            <img src="{{asset('/captins/car_licenses_1/'.$c->car_license_1)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->car_licenses_1}}">
                                            <img src="{{asset('/captins/car_licenses_1/'.$c->car_license_1)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->car_license_1 !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#car_license_1{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==car_license_1==--}}
                                    <div class="modal fade" id="car_license_1{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/car_licenses_1/'.$c->car_license_1)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==car_license_1==--}}

                                    <td>
                                        <a class="lightbox" href="#{{$c->car_licenses_2}}"  >
                                            <img src="{{asset('/captins/car_licenses_2/'.$c->car_license_2)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->car_licenses_2}}">
                                            <img src="{{asset('/captins/car_licenses_2/'.$c->car_license_2)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->car_license_2 !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#car_license_2{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==car_license_2==--}}
                                    <div class="modal fade" id="car_license_2{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/car_licenses_2/'.$c->car_license_2)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==car_license_2==--}}

                                    <td>
                                        <a class="lightbox" href="#{{$c->car_image}}"  >
                                            <img src="{{asset('/captins/car_images/'.$c->car_image)}}" style="width: 50px; height: 50px;margin-top: 0px" />
                                        </a>
                                        <div class="lightbox-target" id="{{$c->car_image}}">
                                            <img src="{{asset('/captins/car_images/'.$c->car_image)}}"/>
                                            <a class="lightbox-close" href="#"></a>
                                        </div>
                                        @if($c->car_image !== null)
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#car_image{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        @endif
                                    </td>
                                    {{--==car_image==--}}
                                    <div class="modal fade" id="car_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{asset('/captins/car_images/'.$c->car_image)}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    {{--==car_image==--}}


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

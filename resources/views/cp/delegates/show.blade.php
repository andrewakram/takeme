@extends('cp.index')
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
                                الطلبات
                                ({{sizeof($orders)}})
                                [{{$type}}]
{{--                                <a href="{{route('accept-orders')}}" class="btn btn-primary"><span>الطلبات المقبولة</span></a>--}}
{{--                                <a href="{{route('onway-orders')}}" class="btn btn-warning"><span>الطلبات في الطريق</span></a>--}}
{{--                                <a href="{{route('finished-orders')}}" class="btn btn-success"><span>الطلبات المنتهية</span></a>--}}
{{--                                <a href="{{route('cancelled-orders')}}" class="btn btn-danger"><span>الطلبات الملغية</span></a>--}}
{{--                                --}}
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاعلانات</li>
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
                        <table class="table" id="myTable">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">العميل</th>
                                <th scope="col">المندوب</th>
                                <th scope="col">القسم</th>
                                <th scope="col"> موقع البدء</th>
                                <th scope="col"> موقع الانهاء</th>
                                <th scope="col">وقت التوصيل</th>
                                <th scope="col"> تكلفة الطلب</th>
                                <th scope="col"> تكلفة التوصيل</th>
                                <th scope="col"> تاريخ الطلب</th>
                                <th scope="col"> تفاصيل الطلب</th>

                            </tr>
                            </thead>
                            <tbody height="200px">
                            @foreach($orders as $c)
                                <tr id="main_cat_{{$c->id}}">
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
                                        @if($c->department_id == 2)
                                            <b class="badge badge-info">متاجر</b>
                                        @elseif($c->department_id == 3)
                                            <b class="badge badge-warning">توصيل<br>طلبات</br>
                                        @endif
                                    </td>
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
                                    <td>
                                        <button title="عرض التفاصيل" type="button" class="btn btn-success"
                                                data-toggle="modal" data-target="#show{{$c->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-eye" color="white" data-toggle="modal">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>

                                    </td>

                                    <div class="modal fade" id="show{{$c->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تفاصيل الطلب</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="model_id" value="{{$c->id}}">


                                                <div class="modal-body">

                                                    @if(isset($c->order_products) && sizeof($c->order_products) > 0)
                                                        @foreach($c->order_products as $product)
                                                            <div class="form-group row">
                                                                <div class="col-lg-1">
                                                                    <b>الاسم:</b>
                                                                </div>
                                                                <div class="col-lg-3" style="text-align: right">
                                                                    <span>{{$product->name}}</span>
                                                                </div>


                                                                <div class="col-lg-1">
                                                                    <b>السعر:</b>
                                                                </div>
                                                                <div class="col-lg-2" style="text-align: right">
                                                                    <span class="badge badge-dark">{{$product->price_after}}</span>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    <input name="has_sizes" readonly
                                                                           type="checkbox" {{$product->has_sizes == 1 ? "checked" : ""}} >
                                                                </div>
                                                                <div class="col-lg-2" style="text-align: right">
                                                                    <span> المنتج له احجام ؟ </span>
                                                                </div>

                                                                <div class="col-lg-2" style="text-align: left">
                                                                    @if($product->image != "")
                                                                        <img src="{{$product->image}}" width="50px" height="50px">
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </div>


                                                            </div>

                                                            <div class="form-group row">

                                                                <div class="col-lg-1">
                                                                    <b>الوصف:</b>
                                                                </div>
                                                                <div class="col-lg-11" style="text-align: right">
                                                                    <span>{{$product->description}}</span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-lg-1">
                                                                    <b>الاختيارات:</b>
                                                                </div>
                                                                <div class="col-lg-11" style="text-align: right">
                                                                    @if(isset($product->variations) && sizeof($product->variations) > 0)
                                                                        <div class="row ">
                                                                            <div class="col-lg-8 alert alert-dark"><b>الاسم</b></div>
                                                                            <div class="col-lg-1 alert alert-dark"><b>النوع</b></div>
                                                                            <div class="col-lg-1 alert alert-dark"><b>اجباري</b></div>
                                                                            <div class="col-lg-1 alert alert-dark"><b>العمليات</b></div>
                                                                        </div>

                                                                        @foreach($product->variations as $variation)
                                                                            <div class="row">
                                                                                <div class="col-lg-8 alert alert-light">
                                                                                    {{$variation->name}}
                                                                                    <br>
                                                                                    <br>
                                                                                    <br>
                                                                                    @if(isset($variation->options) && sizeof($variation->options) > 0)
                                                                                        <div class="row ">
                                                                                            <div class="col-lg-2"></div>
                                                                                            <div class="col-lg-6"><b>الاضافات: </b></div>
                                                                                        </div>
                                                                                        <div class="row ">
                                                                                            <div class="col-lg-2 "></div>
                                                                                            <div class="col-lg-7 alert alert-dark"><b>الاسم</b></div>
                                                                                            <div class="col-lg-2 alert alert-dark"><b>السعر</b></div>
                                                                                        </div>
                                                                                        @foreach($variation->options as $option)
                                                                                            <div class="row">
                                                                                                <div class="col-lg-2"></div>
                                                                                                <div class="col-lg-7 alert alert-light">
                                                                                                    {{$option->name}}
                                                                                                </div><div class="col-lg-2 alert alert-light">
                                                                                                    {{$option->price}}
                                                                                                </div><div class="col-lg-1 alert alert-light">
                                                                                                    <button title="حذف الاضافة" type="button"
                                                                                                            class=" btn-danger"
                                                                                                            data-toggle="modal"
                                                                                                            data-target="#delete_option{{$option->id}}"
                                                                                                            style="padding: 2px">
                                                                                                        <i class="fa fa-trash"></i>
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>

                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                                <div class="col-lg-1 alert alert-light">{{$variation->type == 0 ? "اختيار واحد" : "اختيار من متعدد"}}</div>
                                                                                <div class="col-lg-1 alert alert-light">
                                                                                    @if($variation->required == 1)
                                                                                        <i class='font-success show icon-check'></i>
                                                                                    @else
                                                                                        <i class="font-danger show icon-close"></i>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="modal fade" id="addOPtion{{$variation->id}}" tabindex="-1" role="dialog"
                                                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <h5 class="modal-title" id="exampleModalLabel">اضافة اضافة</h5>
                                                                                            <button type="button" class="close" data-dismiss="modal"
                                                                                                    aria-label="Close">
                                                                                                <span aria-hidden="true">&times;</span>
                                                                                            </button>
                                                                                        </div>
                                                                                        <form class="form-horizontal needs-validation was-validated"
                                                                                              method="post" action="{{route('addOption')}}"
                                                                                              enctype="multipart/form-data">
                                                                                            {{csrf_field()}}
                                                                                            <div class="modal-body">

                                                                                                <input name="variation_id" value="{{$variation->id}}" hidden>

                                                                                                <div class="form-group row">
                                                                                                    <label class="col-lg-12 control-label text-lg-right"
                                                                                                           for="textinput">الاسم </label>
                                                                                                    <div class="col-lg-12">
                                                                                                        <input name="name" type="text"
                                                                                                               placeholder="الاسم "
                                                                                                               class="form-control btn-square" required
                                                                                                               oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="form-group row">
                                                                                                    <label class="col-lg-12 control-label text-lg-right"
                                                                                                           for="textinput">السعر </label>
                                                                                                    <div class="col-lg-12">
                                                                                                        <input name="price" type="text"
                                                                                                               placeholder="السعر "
                                                                                                               class="form-control btn-square" required
                                                                                                               oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>



                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                                                                    اغلاق
                                                                                                </button>
                                                                                                <button class="btn btn-primary">حفظ</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>


                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            <hr>
                                                        @endforeach
                                                    @endif




                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$trips->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title rtl" id="exampleModalLongTitle">الموقع</h5>

                </div>
                <div class="modal-body">
                    <div id="oneordermap"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>

                </div>
            </div>
        </div>
    </div>



@endsection

@section('mapLocation')
    <script type="text/javascript" src="{{asset('js/map.script.js')}}"></script>
    <script>
        var lato;
        var lngo;
        $(document).on('click', '.mapBtnClick', function () {
            lato = parseFloat($(this).attr('lato'));
            lngo = parseFloat($(this).attr('lngo'));
            console.log(lato);
            console.log(lngo);
            initMap(lato, lngo);
        });

    </script>


    <!--++++++++++++++++++++++++++++++++++-->
    <script>
        var marker = null;

        function initMap(lato, lngo) {
            var map = new google.maps.Map(document.getElementById('oneordermap'), {
                zoom: 7,
                center: {lat: lato, lng: lngo}
            });
            var MaekerPos = new google.maps.LatLng(lato, lngo);
            marker = new google.maps.Marker({
                position: MaekerPos,
                map: map
            });
        }
    </script>


    {{--map--}}
    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script>



    <script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPN_XufKy-QTSCB68xFJlqtUjHQ8m6uUY&libraries=places&callback=initMap">
    </script>
    <script>
        $(document).ready(function () {
            $('.count').prop('disabled', true);
            $(document).on('click', '.plus', function () {
                $('.count').val(parseInt($('.count').val()) + 1);
            });
            $(document).on('click', '.minus', function () {
                $('.count').val(parseInt($('.count').val()) - 1);
                if ($('.count').val() == 0) {
                    $('.count').val(1);
                }
            });
        });
    </script>



    {{--map--}}


@endsection

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
                                الرحلات
                                ({{sizeof($trips)}})
                                @if(isset($trips[0]->user->id))
                                <a href="{{asset('finished-user-trips/'.$trips[0]->user->id)}}" class="btn btn-primary"><span>الرحلات المنتهية</span></a>
                                @endif
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
                                <th scope="col">نوع الرحلة</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">العميل</th>
                                <th scope="col">السائق</th>
                                <th scope="col"> موقع البدء </th>
                                <th scope="col"> موقع الانهاء</th>
                                <th scope="col">وقت الرحلة</th>
                                <th scope="col"> وقت الانتظار</th>
                                <th scope="col"> طريقة الدفع </th>
                                <th scope="col"> تكلفة الرحلة </th>
                                {{--<th scope="col"> تقييم العميل </th>
                                <th scope="col"> تقييم السائق </th>--}}
                                <th scope="col">توقيت الطلب</th>
                            </tr>
                            </thead>
                            <tbody height="200px">
                            @foreach($trips as $c)
                                <tr id="main_cat_{{$c->id}}" >
                                    <td>{{$c->id}}</td>
                                    <td>
                                        @if($c->type == "urgent")
                                            عاجلة
                                        @else
                                            مؤجلة
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->status == 1)
                                            في انتظار السائق
                                        @elseif($c->status == 2)
                                            تم بدئ الرحلة
                                        @elseif($c->status == 3)
                                            تم انهاء الرحلة
                                        @elseif($c->status == 4)
                                            تم الغاء الرحلة
                                        @endif
                                    </td>
                                    @if(($c->user) != null)
                                        <td>
                                            {{$c->user->id}}
                                            <br>
                                            {{$c->user->name}}
                                            <br>
                                            <img src="{{$c->user->image}}"  width="50px" height="50px">
                                        </td>
                                    @else
                                        <td>
                                            -
                                        </td>
                                    @endif
                                    @if(($c->driver) != null)
                                        <td>
                                            {{$c->driver->id}}
                                            <br>
                                            {{$c->driver->name}}
                                            <br>
                                            <img src="{{$c->driver->image}}"  width="50px" height="50px">
                                        </td>
                                    @else
                                        <td>
                                            -
                                        </td>
                                    @endif
                                    <td >
                                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$c->start_lat}},{{$c->start_lng}}">
                                            <i class="icon-location-pin"></i>
                                            <br>
                                            {{$c->start_address}}
                                        </a>

                                    </td>
                                    <td >
                                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$c->end_lat}},{{$c->end_lng}}">
                                            <i class="icon-location-pin"></i>
                                            <br>
                                            {{$c->end_address}}
                                        </a>

                                    </td>

                                    <td>{{$c->trip_time}}</td>
                                    <td>{{$c->waiting_time}}</td>
                                    <td>
                                        @if($c->payment ==0)
                                            نقدا
                                        @else
                                            فيزا
                                            @if($c->pay_status == 0)
                                                <b class="badge btn-danger">
                                                    لم يتم الدفع
                                                </b>
                                            @endif
                                            @if($c->pay_status == 1)
                                                <b class="badge btn-success">
                                                    تم تاكيد الدفع
                                                </b>
                                            @endif
                                            @if($c->pay_status == 2)
                                                <form class="form-horizontal" method="post" action="{{route('checkPaymentSrtatus')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <input type="hidden" name="trip_id" value="{{$c->id}}">
                                                    <button class="btn btn-warning" type="submit">تأكيد الدفع</button>
                                                </form>
                                            @endif
                                            @if($c->pay_status == 3)
                                                <b class="badge btn-primary">
                                                    فشلت عملية الدفع
                                                </b>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$c->trip_total}}</td>
                                    {{--<td>{{$c->user_rate}}</td>
                                    <td>{{$c->driver_rate}}</td>--}}
                                    <td>{{$c->created_at}}</td>


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


    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
    <script type="text/javascript"  src="{{asset('js/map.script.js')}}"></script>
    <script>
        var lato;
        var lngo;
        $(document).on('click', '.mapBtnClick', function () {
            lato = parseFloat($(this).attr('lato'));
            lngo = parseFloat($(this).attr('lngo'));
            console.log(lato);
            console.log(lngo);
            initMap(lato,lngo);
        });

    </script>


    <!--++++++++++++++++++++++++++++++++++-->
    <script>
        var marker = null;
        function initMap(lato,lngo) {
            var map = new google.maps.Map(document.getElementById('oneordermap'), {
                zoom: 7,
                center: {lat: lato, lng: lngo }
            });
            var MaekerPos = new google.maps.LatLng(lato , lngo);
            marker = new google.maps.Marker({
                position: MaekerPos,
                map: map
            });
        }
    </script>


    {{--map--}}
    <script>
        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        })
    </script>



    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPN_XufKy-QTSCB68xFJlqtUjHQ8m6uUY&libraries=places&callback=initMap">
    </script>
    <script>
        $(document).ready(function() {
            $('.count').prop('disabled', true);
            $(document).on('click', '.plus', function() {
                $('.count').val(parseInt($('.count').val()) + 1);
            });
            $(document).on('click', '.minus', function() {
                $('.count').val(parseInt($('.count').val()) - 1);
                if ($('.count').val() == 0) {
                    $('.count').val(1);
                }
            });
        });
    </script>



    {{--map--}}


@endsection

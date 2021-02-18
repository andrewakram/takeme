@extends('cp.reports.reportIndex')
@section('offersReport')

    <div class="box-header">
        <h3 class="box-title"><b> الرحلات</b> &nbsp;&nbsp;( {{sizeof($users)}} )&nbsp;&nbsp;</h3>

        <div style="padding-left: 500px" class="btn btn-default">
            @if(sizeof($users) > 0)
                    <a href="{{route('tripsInvoice')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> طباعة </a>
            @endif
        </div>
        <span class="pull-left" style="padding-left: 100px">
            <img src="{{asset("default.png")}}" style="width:80px; height:60px">
        </span>
    </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover">

@if(sizeof($users) > 0)
                                <thead>
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
                                <tbody id="myTable">
                                @foreach($users as $c)
                                    <tr>
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
                                        <td lato="{{$c->start_lat}}" lngo="{{$c->start_lng}}" class="mapBtnClick">
                                            <a href="javascript:void(0);" class="fas fa-map-marker-alt" class="fadeIn fourth ref2 map-btn" value="Detect your location " data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="icon-location-pin"></i>
                                                <br>
                                                {{$c->start_address}}
                                            </a>
                                        </td>
                                        <td lato="{{$c->end_lat}}" lngo="{{$c->end_lng}}" class="mapBtnClick">
                                            <a href="javascript:void(0);" class="fas fa-map-marker-alt" class="fadeIn fourth ref2 map-btn" value="Detect your location " data-toggle="modal" data-target="#exampleModalCenter">
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
                                            @endif
                                        </td>
                                        <td>{{isset($c->trip_total) ? $c->trip_total : "-"}}</td>
                                        {{--<td>{{$c->user_rate}}</td>
                                        <td>{{$c->driver_rate}}</td>--}}
                                        <td>{{$c->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
 @else
                <thead>

                <tr>
                    <b>عذرا لا توجد بيانات</b>
                </tr>
                </thead>
@endif
                            </table>
                        </div>
                        <!-- /.box-body -->



@endsection

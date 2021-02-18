@extends('cp.reports.reportIndex')
@section('offersReport')

    <div class="box-header">
        <h3 class="box-title"><b> طلبات المتاجر العادية</b> &nbsp;&nbsp;( {{sizeof($users)}} )&nbsp;&nbsp;</h3>

        <div style="padding-left: 500px" class="btn btn-default">
            @if(sizeof($users) > 0)
                    <a href="{{route('ordersNormalInvoice')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> طباعة </a>
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
                                <th scope="col">الحالة</th>
                                <th scope="col">العميل</th>
                                <th scope="col">المندوب</th>
                                <th scope="col"> موقع البدء</th>
                                <th scope="col"> موقع الانهاء</th>
                                <th scope="col">وقت التوصيل</th>

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

                                    <td >
                                        <span class="badge badge-success">
                                            {{isset($c->offer->offer) ? $c->offer->offer : "-"}}
                                        </span>
                                        <br/>
                                        {{isset($c->country->currency) ? $c->country->currency : "-"}}
                                    </td>
                                    <td >{{$c->created_at}}</td>
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

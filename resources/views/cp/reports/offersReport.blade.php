@extends('cp.reports.reportIndex')
@section('offersReport')

    <div class="box-header">
        <h3 class="box-title"><b> الاعلانات</b> &nbsp;&nbsp;( {{sizeof($users)}} )&nbsp;&nbsp;</h3>

        <div style="padding-left: 500px" class="btn btn-default">
            @if(sizeof($users) > 0)
                    <a href="{{route('offersInvoice')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> طباعة </a>
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
                                    <th scope="col">الاسم بالعربية</th>
                                    <th scope="col">الاسم بالانجليزية</th>
                                    <th scope="col"> السعر القديم</th>
                                    <th scope="col"> السعر الجديد</th>
                                    <th scope="col"> الوصف </th>
                                    <th scope="col"> الصورة</th>
                                    <th scope="col"> اسم المتجر</th>
                                    <th scope="col"> تاريخ انشاء الاعلان</th>
                                </tr>
                                </thead>
                                <tbody id="myTable">
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name_ar}}</td>
                                        <td>{{$user->name_en}}</td>
                                        <td>{{$user->old_price}}</td>
                                        <td>{{$user->new_price}}</td>
                                        <td>{{$user->description_en}} / {{$user->description_ar}}</td>
                                        @if($user->image != NULL)
                                            <th><img src="{{$user->image}}"  width="50px" height="50px"></th>
                                        @else
                                            <th> - </th>
                                        @endif
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->created_at}}</td>
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

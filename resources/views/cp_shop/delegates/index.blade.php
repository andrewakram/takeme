@extends('cp_shop.index')
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
                                المناديب
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المستخدمين</li>
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
                                <th scope="col" style="text-align: right">#</th>
                                <th scope="col"> الاسم </th>
                                <th scope="col"> الصورة </th>
                                <th scope="col">الموبايل </th>
                                <th scope="col"> البريد الالكتروني</th>
{{--                                <th scope="col"> الدولة </th>--}}
{{--                                <th scope="col"> المحفظة </th>--}}
{{--                                <th scope="col"> النقاط </th>--}}
{{--                                <th scope="col"> الحالة </th>--}}
{{--                                <th scope="col">الاختيارات</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="{{$c->suspend == 1 ? 'table-danger' :''}}">
                                    <td style="text-align: right">
                                        {{$c->id}} <br>

                                    </td>
                                    <td>{{$c->f_name}} {{$c->l_name}}</td>
                                    @if($c->image != NULL)
                                        <th><img src="{{$c->image}}"  width="40px" height="40px"></th>
                                    @else
                                        <th> - </th>
                                    @endif
                                    <td>{{$c->phone}}</td>
                                    <td>{{$c->email}}</td>
{{--                                    <td>{{isset($c->country->name) ? $c->country->name : "-"}}</td>--}}
{{--                                    <td>{{isset($c->wallet) ? $c->wallet : "-"}}</td>--}}
{{--                                    <td>{{isset($c->points) ? $c->points : "-"}}</td>--}}
{{--                                    <td>--}}
{{--                                        @if($c->active == 1)--}}
{{--                                            <i class="font-success show icon-check"></i>--}}
{{--                                        @else--}}
{{--                                            <i class="font-danger show icon-close"></i>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @if($c->suspend == 0)--}}
{{--                                            <a href="{{route('editClientStatus',$c->id)}}" >--}}
{{--                                                <button title="ايقاف " class="btn btn-danger">--}}
{{--                                                    <i class="fa fa-minus-circle"></i>--}}
{{--                                                </button>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a href="{{route('editClientStatus',$c->id)}}" >--}}
{{--                                                <button title="اعادة تشغيل " class="btn btn-success">--}}
{{--                                                    <i class="fa fa-plus-circle"></i>--}}
{{--                                                </button>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}

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

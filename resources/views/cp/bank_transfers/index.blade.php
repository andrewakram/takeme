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
                                التحويلات البنكية
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
{{--                        <div style="float: left">--}}
{{--                            --}}{{--@if(admin()->hasPermissionTo('Add country'))--}}
{{--                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>--}}
{{--                                اضافة سبب--}}
{{--                            </button>--}}
{{--                            --}}{{--@endif--}}
{{--                        </div>--}}

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

                                <th scope="col"> اسم البنك </th>
                                <th scope="col"> رقم التحويل </th>
                                <th scope="col">قيمة التحويل</th>
                                <th scope="col">الصورة</th>
                                <th scope="col">بيانات السائق</th>

{{--                                <th scope="col">الاختيارات</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>

                                    <td>{{$c->bank_name}}</td>
                                    <td>{{$c->transfer_no}}</td>
                                    <td>{{$c->transfer_value}}</td>
                                    <td>
                                        <img src="{{$c->image}}"  width="40px" height="40px">
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td dir="rtl">
                                        - {{$c->user_id}}# <br>
                                        - {{$c->name}} <br>
                                        - {{$c->email}} <br>
                                        - {{$c->phone}} <br>
                                    </td>

{{--                                    <td>--}}
{{--                                        --}}{{--@if(admin()->hasPermissionTo('Edit City'))--}}
{{--                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">--}}
{{--                                            <i class="fa fa-edit"></i>--}}
{{--                                        </button>--}}
{{--                                        --}}{{--@endif--}}

{{--                                        <a href="{{route('deleteReason',$c->id)}}" data-original-title="" title="">--}}
{{--                                            <button title="" class="btn btn-danger" data-original-title="حذف">--}}
{{--                                                <i class="fa fa-minus-circle"></i>--}}
{{--                                            </button>--}}
{{--                                        </a>--}}
{{--                                    </td>--}}

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <img src="{{$c->image}}">
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$countries->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>


@endsection

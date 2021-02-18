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
                                اوقات الذروة
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة توقيت
                            </button>
                            {{--@endif--}}
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
                                <th scope="col">الدولة</th>

                                <th scope="col">التوقيت من</th>
                                <th scope="col">التوقيت الي</th>

                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->country_name_en}} / {{$c->country_name_ar}}</td>

                                    <td>{{$c->from}}</td>
                                    <td>{{$c->to}}</td>

                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {{--@endif--}}

                                        <a href="{{route('deleteRushhour',$c->id)}}" data-original-title="" title="">
                                            <button title="" class="btn btn-danger" data-original-title="حذف">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                        </a>
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل التوقيت</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editRushhour')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="rushhour_id" value="{{$c->id}}">


                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدولة </label>
                                                            <div class="col-lg-12">
                                                                <input  type="text" placeholder="الدولة" class="form-control btn-square" value="{{$c->country_name_en}} / {{$c->country_name_ar}}" readonly>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])



                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  الوقت من</label>
                                                            <div class="col-lg-12">
                                                                <input name="from" class="form-control digits" type="time" value="{{$c->from}}" data-original-title="" title="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الوقت الي</label>
                                                            <div class="col-lg-12">
                                                                <input name="to" class="form-control digits" type="time" value="{{$c->to}}" data-original-title="" title="">
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
                    </div>{{--{{$countries->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة توقيت</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('rushhours.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row {{--{{ $errors->has('ar_name') ? ' has-error' : '' }}--}}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدولة </label>
                            <div class="col-lg-12">
                                <select class="form-control" id="row-1-office" size="1" name="country_id" required>
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" >{{$country->name_en}} / {{$country->name_ar}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  الوقت من</label>
                            <div class="col-lg-12">
                                <input name="from" class="form-control digits" type="time" data-original-title="" title="" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الوقت الي</label>
                            <div class="col-lg-12">
                                <input name="to" class="form-control digits" type="time" data-original-title="" title="" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                    </div>




                    <div class="modal-footer">
                        <button type="reset" class="btn btn-dark" data-dismiss="modal">اغلاق</button>
                        <button class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

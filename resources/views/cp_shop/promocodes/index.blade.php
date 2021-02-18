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
                                أكواد الخصم
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة كود خصم
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

                                <th scope="col">الكود</th>
                                <th scope="col">القيمة</th>
                                <th scope="col">نوع الخصم</th>
                                <th scope="col">الدول</th>
                                <th scope="col">مستويات السيارات</th>
                                <th scope="col">عدد مرات الاستخدام</th>
                                <th scope="col">تاريخ الانتهاء</th>
                                <th scope="col">الوصف بالعربية</th>
                                <th scope="col">الوصف بالانجليزية</th>

                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>

                                    <td>{{$c->code}}</td>
                                    <td>{{$c->value}}</td>
                                    <td>{{$c->type == 0 ? "خصم ثابت " : "خصم نسبة"}}</td>
                                    <td>
{{--                                        {{$c->country_ids}}--}}
                                        @foreach($c->countries as $country)
                                        <span>- {{$country->name_ar}} / {{$country->name_en}}</span> <br>
                                        @endforeach
                                    </td>
                                    <td>
{{--                                        {{$c->car_level_ids}}--}}
                                        @foreach($c->car_levels as $car_level)
                                            <span>- {{$car_level->name}}</span> <br>
                                        @endforeach
                                    </td>
                                    <td>{{$c->expire_times}}</td>
                                    <td>{{$c->expire_at}}</td>
                                    <td>{{$c->ar_desc}}</td>
                                    <td>{{$c->en_desc}}</td>

                                    <td>

                                        <a href="{{route('deletePromo',$c->id)}}" data-original-title="" title="">
                                            <button title="" class="btn btn-danger" data-original-title="حذف">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                        </a>
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل السبب</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editReason')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="reason_id" value="{{$c->id}}">


                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">السبب</label>
                                                            <div class="col-lg-12">
                                                                <input name="reason" class="form-control" type="text" value="{{$c->reason}}" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد</label>
                                                            <div class="col-lg-12">
                                                                <select name="is_captin" class="form-control digits" id="exampleFormControlSelect9">
                                                                    <option value="0" {{$c->is_captin == 0 ? "selected" : ""}}>خاص بالمستخدم</option>
                                                                    <option value="1" {{$c->is_captin == 1 ? "selected" : ""}}>خاص بالسائق</option>
                                                                </select>
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
                    <h5 class="modal-title" id="exampleModalLabel">اضافة كود خصم جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('promocodes.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">



                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">كود الخصم</label>
                            <div class="col-lg-12">
                                <input name="code" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">قيمة الخصم</label>
                            <div class="col-lg-12">
                                <input name="value" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد نوع الخصم</label>
                            <div class="col-lg-12">
                                <select name="type" class="form-control digits" id="exampleFormControlSelect9" required>
                                    <option value="0" >خصم ثابت</option>
                                    <option value="1" >خصم بالنسبة</option>
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right">الدول التي يعمل بها الخصم</label>
                            <div class="col-md-12 breadcrumb" style="background-color:#c1c1c1;">
                                @foreach($countries as $country)
                                    <label class="d-block" for="chk-ani">
                                        <input type="checkbox" name="country[]" value="{{$country->id}}" class="checkbox_animated" >
                                    </label>
                                    {{$country->name_ar}} / {{$country->name_en}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right">مستويات السيارات التي يعمل بها الخصم</label>
                            <div class="col-md-12 breadcrumb" style="background-color:#c1c1c1;">
                                @foreach($car_levels as $car_level)
                                    <label class="d-block" for="chk-ani">
                                        <input type="checkbox" name="car_level[]" value="{{$car_level->id}}" class="checkbox_animated" >
                                    </label>
                                    {{$car_level->name}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">عدد مرات الاستخدام للكود</label>
                            <div class="col-lg-12">
                                <input name="expire_times" class="form-control digits" type="number" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">تاريخ انتهاء صلاحية الكود</label>
                            <div class="col-lg-12">
                                <input name="expire_at" class="form-control digits" type="date" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف بالانجليزية</label>
                            <div class="col-lg-12">
                                <input name="en_desc" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف بالعربية</label>
                            <div class="col-lg-12">
                                <input name="ar_desc" class="form-control digits" type="text" required>
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

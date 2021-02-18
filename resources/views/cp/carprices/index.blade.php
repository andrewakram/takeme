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
                                التعريفة
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة تعريفة
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
                                <th scope="col">مستوي العربة</th>

                                <th scope="col"> تعريفة فتح العداد</th>
                                <th scope="col"> تعريفة الانتظار للرحلة</th>
                                <th scope="col"> تعريفة الوحدة (الكيلو)</th>

                                <th scope="col"> تعريفة فتح العداد في وقت الذروة</th>
                                <th scope="col"> تعريفة الانتظار للرحلة في وقت الذروة</th>
                                <th scope="col"> تعريفة الوحدة (الكيلو) في وقت الذروة</th>


                                <th scope="col">تعريفة الغاء الرحلة</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->country_name_en}} / {{$c->country_name_ar}}</td>
                                    <td>{{$c->car_level_name}}</td>

                                    <td>{{$c->start_trip_unit}}</td>
                                    <td>{{$c->waiting_trip_unit}}</td>
                                    <td>{{$c->distance_trip_unit}}</td>

                                    <td>{{$c->rush_start_trip_unit}}</td>
                                    <td>{{$c->rush_waiting_trip_unit}}</td>
                                    <td>{{$c->rush_distance_trip_unit}}</td>


                                    <td>{{$c->cancellation_trip_unit}}</td>
                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {{--@endif--}}
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل التعريفة</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editCarprice')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="country_car_level_id" value="{{$c->id}}">


                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدولة </label>
                                                            <div class="col-lg-12">
                                                                <input name="name" type="text" placeholder="الدولة " class="form-control btn-square" value="{{$c->country_name_en}} / {{$c->country_name}}" readonly>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])

                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">مستوي العربة </label>
                                                            <div class="col-lg-12">
                                                                <input name="name" type="text" placeholder="مستوي العربة " class="form-control btn-square" value="{{$c->car_level_name}}" readonly>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])


                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة فتح العداد</label>
                                                            <div class="col-lg-12">
                                                                <input name="start_trip_unit" type="text" placeholder="  تعريفة فتح العداد" class="form-control btn-square"  value="{{$c->start_trip_unit}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الانتظار للرحلة</label>
                                                            <div class="col-lg-12">
                                                                <input name="waiting_trip_unit" type="text" placeholder="  تعريفة الانتظار للرحلة" class="form-control btn-square"  value="{{$c->waiting_trip_unit}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الوحدة (الكيلو)</label>
                                                            <div class="col-lg-12">
                                                                <input name="distance_trip_unit" type="text" placeholder="  تعريفة الوحدة (الكيلو)" class="form-control btn-square"  value="{{$c->distance_trip_unit}}" required>
                                                            </div>
                                                        </div>


                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة فتح العداد في وقت الذروة</label>
                                                            <div class="col-lg-12">
                                                                <input name="rush_start_trip_unit" type="text" placeholder="  تعريفة فتح العداد في وقت الذروة" class="form-control btn-square"  value="{{$c->rush_start_trip_unit}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الانتظار للرحلة في وقت الذروة</label>
                                                            <div class="col-lg-12">
                                                                <input name="rush_waiting_trip_unit" type="text" placeholder="  تعريفة الانتظار للرحلة في وقت الذروة" class="form-control btn-square"  value="{{$c->rush_waiting_trip_unit}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الوحدة (الكيلو) في وقت الذروة</label>
                                                            <div class="col-lg-12">
                                                                <input name="rush_distance_trip_unit" type="text" placeholder="  تعريفة الوحدة (الكيلو) في وقت الذروة" class="form-control btn-square"  value="{{$c->rush_distance_trip_unit}}" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الغاء الرحلة</label>
                                                            <div class="col-lg-12">
                                                                <input name="cancellation_trip_unit" type="text" placeholder="تعريفة الغاء الرحلة" class="form-control btn-square"  value="{{$c->cancellation_trip_unit}}" required>
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
                    <h5 class="modal-title" id="exampleModalLabel">اضافة تعريفة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('carprices.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row {{--{{ $errors->has('ar_name') ? ' has-error' : '' }}--}}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدولة </label>
                            <div class="col-lg-12">
                                <select class="form-control" id="row-1-office" size="1" name="country_id">
                                    @foreach($countries as $country)
                                    <option value="{{$country->id}}" >{{$country->name_en}} / {{$country->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>


                        <div class="form-group row {{--{{ $errors->has('ar_name') ? ' has-error' : '' }}--}}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">مستوي العربة </label>
                            <div class="col-lg-12">
                                <select class="form-control" id="row-1-office" size="1" name="car_level_id">
                                    @foreach($levels as $level)
                                        <option value="{{$level->id}}" >{{$level->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>



                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة فتح العداد</label>
                            <div class="col-lg-12">
                                <input name="start_trip_unit" type="text" placeholder="  تعريفة فتح العداد" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الانتظار للرحلة</label>
                            <div class="col-lg-12">
                                <input name="waiting_trip_unit" type="text" placeholder="  تعريفة الانتظار للرحلة" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الوحدة (الكيلو)</label>
                            <div class="col-lg-12">
                                <input name="distance_trip_unit" type="text" placeholder="  تعريفة الوحدة (الكيلو)" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة فتح العداد في وقت الذروة</label>
                            <div class="col-lg-12">
                                <input name="rush_start_trip_unit" type="text" placeholder="  تعريفة فتح العداد في وقت الذروة" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الانتظار للرحلة في وقت الذروة</label>
                            <div class="col-lg-12">
                                <input name="rush_waiting_trip_unit" type="text" placeholder="  تعريفة الانتظار للرحلة في وقت الذروة" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الوحدة (الكيلو) في وقت الذروة</label>
                            <div class="col-lg-12">
                                <input name="rush_distance_trip_unit" type="text" placeholder="  تعريفة الوحدة (الكيلو) في وقت الذروة" class="form-control btn-square"   required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('code') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">  تعريفة الغاء الرحلة</label>
                            <div class="col-lg-12">
                                <input name="cancellation_trip_unit" type="text" placeholder="تعريفة الغاء الرحلة" class="form-control btn-square"   required>
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

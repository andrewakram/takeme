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
                                المحافظات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المحافظات</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add city'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة محافظة
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
                                <th scope="col">الاسم بالعربية</th>
                                <th scope="col">الاسم بالانجليزية</th>
                                <th scope="col">الدول التابع لها</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($govs as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->name_ar}}</td>
                                    <td>{{$c->name_en}}</td>
                                    <td>{{$c->country_name_en}} / {{$c->country_name_ar}}</td>
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
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل محافظة</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editGov')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="gov_id" value="{{$c->id}}">


                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">لاسم بالعربية</label>
                                                            <div class="col-lg-12">
                                                                <input name="name_ar" type="text" placeholder="الاسم بالعربية" class="form-control btn-square" value="{{$c->name_ar}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])

                                                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالنجليزية</label>
                                                            <div class="col-lg-12">
                                                                <input name="name_en" type="text" placeholder="الاسم بالنجليزية" class="form-control btn-square"  value="{{$c->name_en}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'en_name'])

                                                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدول التابع لها</label>
                                                            <div class="col-lg-12">
                                                                <select name="country_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                    @foreach($countries as $gov)
                                                                        <option value="{{$gov->id}}" {{$gov->id == $c->gov_id ? "selected" : ""}}> {{$gov->name_en}} / {{$gov->name_ar}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'en_name'])





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
                    </div>{{--{{$govs->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة محافظة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('govs.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالعربية</label>
                            <div class="col-lg-12">
                                <input id="name" name="name_ar" type="text" placeholder="الاسم بالعربية" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالانجليزية</label>
                            <div class="col-lg-12">
                                <input id="name" name="name_en" type="text" placeholder="الاسم بالانجليزية" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الدول التابع لها</label>
                            <div class="col-lg-12">
                                <select name="country_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر الدول</option>
                                    @foreach($countries as $gov)
                                        <option value="{{$gov->id}}"> {{$gov->name_en}} / {{$gov->name_ar}} </option>
                                    @endforeach
                                </select>
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

    <script>
        // function myFunction(id){
        //     $.ajax({
        //         url: 'sub_cats/' + id,
        //         type: "GET",
        //         dataType: "json",
        //
        //         success: function (data) {
        //             // $("#main_cat_"+id).click(function () {
        //             //     $('#sub_cats_'+id).empty();
        //             //     $("#sub_cats_"+id).toggle();
        //             // });
        //             $('#sub_cats_'+id).empty();
        //             $.each(data, function (i, sub_cat) {
        //                 $('#sub_cats_'+id).append(
        //                     '<tr id="third_cat_"'+sub_cat.id+'>'+
        //                     '<td></td>'+
        //                     '<td>' + sub_cat.id + '</td>'+
        //                     '<td>' + sub_cat.ar_name + '</td>'+
        //                     '<td>' + sub_cat.en_name + '</td>'+
        //                     '</tr>'
        //                 );
        //             });
        //         }
        //     });
        // }
    </script>
@endsection

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
                                المنتجات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i
                                        class="icon-plus"></i>
                                اضافة منتج
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
                                <th scope="col">الاسم</th>
                                <th scope="col">الوصف</th>
                                <th scope="col">الاختيارات</th>
                                <th scope="col">الصورة</th>
                                <th scope="col">السعر</th>
                                <th scope="col">القائمة</th>
                                <th scope="col">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($countries as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->name}}</td>
                                    <td>{{$c->description}}</td>
                                    <td>
                                        @if(sizeof($c->variations) > 0)
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td><b>الاسم</b></td>
                                                    <td><b>النوع</b></td>
                                                    <td><b>اجباري</b></td>
                                                    <td><b> . . . </b></td>
                                                </tr>
                                                @foreach($c->variations as $variation)
                                                    <tr>
                                                        <td>{{$variation->name}}</td>
                                                        <td>{{$variation->type == 0 ? "اختيار واحد" : "اختيار من متعدد"}}</td>
                                                        <td>
                                                            @if($variation->required == 1)
                                                                <i class='font-success show icon-check'></i>
                                                            @else
                                                                <i class="font-danger show icon-close"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button title="تعديل الاختيار" type="button" class=" btn-warning"
                                                                    data-toggle="modal"
                                                                    data-target="#edit_var{{$variation->id}}"
                                                                    style="padding: 1px">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button title="حذف الاختيار" type="button" class=" btn-danger"
                                                                    data-toggle="modal"
                                                                    data-target="#delete_var{{$variation->id}}"
                                                                    style="padding: 1px 2px">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>

                                                        <div class="modal fade" id="edit_var{{$variation->id}}" tabindex="-1" role="dialog"
                                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل اختيار</h5>
                                                                        <button type="button" class="close" data-dismiss="modal"
                                                                                aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form class="form-horizontal needs-validation was-validated"
                                                                          method="post" action="{{route('editVariation')}}"
                                                                          enctype="multipart/form-data">
                                                                        {{csrf_field()}}
                                                                        <div class="modal-body">

                                                                            <input name="variation_id" value="{{$variation->id}}" hidden>

                                                                            <div class="form-group row">
                                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                                       for="textinput">الاسم </label>
                                                                                <div class="col-lg-12">
                                                                                    <input name="name" type="text"
                                                                                           placeholder="الاسم "
                                                                                           value="{{$variation->name}}"
                                                                                           class="form-control btn-square" required
                                                                                           oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <div class="col-lg-1">
                                                                                    <input name="required" type="checkbox"
                                                                                    {{$variation->required == 1 ? "checked" : "" }}>
                                                                                </div>
                                                                                <div class="col-lg-9">
                                                                                    <label class="col-lg-12 control-label text-lg-right"
                                                                                           for="textinput"> هذا الاختيار اجباري ؟ </label>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group row">
                                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                                       for="textinput">القائمة </label>
                                                                                <div class="col-lg-12">
                                                                                    <select name="type" class="form-control btn-square">
                                                                                        <option value="0" {{$variation->type == 0 ? "selected" : "" }}>اختيار واحد</option>
                                                                                        <option value="1" {{$variation->type == 1 ? "selected" : "" }}>اختيار من متعدد</option>
                                                                                    </select>
                                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                                                اغلاق
                                                                            </button>
                                                                            <button class="btn btn-primary">حفظ</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal animated fadeIn"
                                                             id="delete_var{{$variation->id}}" tabindex="-1"
                                                             role="dialog" aria-labelledby="exampleModalLabel"
                                                             aria-hidden="true"
                                                             style="text-align:right">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                 role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header btn-danger">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            حذف الحقل</h5>
                                                                        {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                                        {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                                        {{--                                            </button>--}}
                                                                    </div>
                                                                    <form method="post"
                                                                          action="{{route('deleteVariation')}}"
                                                                          class="buttons">
                                                                        {{csrf_field()}}
                                                                        <div class="modal-body">
                                                                            <h4>هل انت متأكد ؟</h4>
                                                                            <h6>
                                                                                انت علي وشك حذف هذا الحقل
                                                                            </h6>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="model_id"
                                                                                   value="{{$variation->id}}">
                                                                            <button class="btn btn-dark" type="button"
                                                                                    data-dismiss="modal">
                                                                                اغلاق
                                                                            </button>
                                                                            <button type="submit"
                                                                                    class="btn btn-primary">تأكيد
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </tr>

                                                @endforeach
                                            </table>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->image != "")
                                            <img src="{{$c->image}}" width="50px" height="50px">
                                            <br>
                                            <a class="badge btn btn-success btn-sm" target="_blank"
                                               href="{{$c->image}}"> عرض</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-dark">{{$c->price_after}}</span>
                                    </td>
                                    <td>
                                        @if($c->menue)
                                            <span class="badge badge-primary">{{$c->menue->name}}</span>
                                        @else
                                            <span class="badge badge-primary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button title="عرض المنتج" type="button" class="btn btn-info"
                                                data-toggle="modal" data-target="#show{{$c->id}}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-eye" color="white" data-toggle="modal">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </button>
                                        <button title="اضافة اختيار" type="button" class="btn btn-success"
                                                data-toggle="modal" data-target="#addVariation{{$c->id}}">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <br>
                                        <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal"
                                                data-target="#delete_{{$c->id}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{--@endif--}}

                                    </td>

                                    <div class="modal fade" id="show{{$c->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تفاصيل المنتج</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="model_id" value="{{$c->id}}">


                                                <div class="modal-body">

                                                    <div class="form-group row">
                                                        <div class="col-lg-1">
                                                            <b>الاسم:</b>
                                                        </div>
                                                        <div class="col-lg-3" style="text-align: right">
                                                            <span>{{$c->name}}</span>
                                                        </div>


                                                        <div class="col-lg-1">
                                                            <b>السعر:</b>
                                                        </div>
                                                        <div class="col-lg-2" style="text-align: right">
                                                            <span class="badge badge-dark">{{$c->price_after}}</span>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <input name="has_sizes" readonly
                                                                   type="checkbox" {{$c->has_sizes == 1 ? "checked" : ""}} >
                                                        </div>
                                                        <div class="col-lg-2" style="text-align: right">
                                                            <span> المنتج له احجام ؟ </span>
                                                        </div>

                                                        <div class="col-lg-2" style="text-align: left">
                                                            @if($c->image != "")
                                                                <img src="{{$c->image}}" width="50px" height="50px">
                                                            @else
                                                                -
                                                            @endif
                                                        </div>


                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-1">
                                                            <b>القائمة:</b>
                                                        </div>
                                                        <div class="col-lg-3" style="text-align: right">
                                                            <span class="badge badge-info">{{$c->menue->name}}</span>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <b>الوصف:</b>
                                                        </div>
                                                        <div class="col-lg-7" style="text-align: right">
                                                            <span>{{$c->description}}</span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <div class="col-lg-1">
                                                            <b>الاختيارات:</b>
                                                            <button title="اضافة اختيار" type="button" class="btn btn-success" style="padding: 5px 10px"
                                                                    data-toggle="modal" data-target="#addVariation{{$c->id}}">
                                                                <i class="fa fa-plus-circle"></i>
                                                            </button>
                                                            <br>
                                                        </div>
                                                        <div class="col-lg-11" style="text-align: right">
                                                            @if(isset($c->variations) && sizeof($c->variations) > 0)
                                                                <div class="row ">
                                                                    <div class="col-lg-8 alert alert-dark"><b>الاسم</b></div>
                                                                    <div class="col-lg-1 alert alert-dark"><b>النوع</b></div>
                                                                    <div class="col-lg-1 alert alert-dark"><b>اجباري</b></div>
                                                                    <div class="col-lg-1 alert alert-dark"><b>العمليات</b></div>
                                                                </div>

                                                                @foreach($c->variations as $variation)
                                                                    <div class="row">
                                                                        <div class="col-lg-8 alert alert-light">
                                                                            {{$variation->name}}
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            @if(isset($variation->options) && sizeof($variation->options) > 0)
                                                                                <div class="row ">
                                                                                    <div class="col-lg-2"></div>
                                                                                    <div class="col-lg-6"><b>الاضافات: </b></div>
                                                                                </div>
                                                                                <div class="row ">
                                                                                    <div class="col-lg-2 "></div>
                                                                                    <div class="col-lg-7 alert alert-dark"><b>الاسم</b></div>
                                                                                    <div class="col-lg-2 alert alert-dark"><b>السعر</b></div>
                                                                                    <div class="col-lg-1 alert alert-dark"><b>X</b></div>
                                                                                </div>
                                                                                @foreach($variation->options as $option)
                                                                                    <div class="row">
                                                                                        <div class="col-lg-2"></div>
                                                                                        <div class="col-lg-7 alert alert-light">
                                                                                            {{$option->name}}
                                                                                        </div><div class="col-lg-2 alert alert-light">
                                                                                            {{$option->price}}
                                                                                        </div><div class="col-lg-1 alert alert-light">
                                                                                            <button title="تعديل الاضافة" type="button" class=" btn-warning"
                                                                                                    data-toggle="modal"
                                                                                                    data-target="#edit_Option{{$option->id}}"
                                                                                                    style="padding: 1px">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </button>
                                                                                            <button title="حذف الاضافة" type="button"
                                                                                                    class=" btn-danger"
                                                                                                    data-toggle="modal"
                                                                                                    data-target="#delete_option{{$option->id}}"
                                                                                                    style="padding: 2px">
                                                                                                <i class="fa fa-trash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="modal fade" id="edit_Option{{$option->id}}" tabindex="-1" role="dialog"
                                                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog" role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل الاضافة</h5>
                                                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                                                            aria-label="Close">
                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                    </button>
                                                                                                </div>
                                                                                                <form class="form-horizontal needs-validation was-validated"
                                                                                                      method="post" action="{{route('editOption')}}"
                                                                                                      enctype="multipart/form-data">
                                                                                                    {{csrf_field()}}
                                                                                                    <div class="modal-body">

                                                                                                        <input name="option_id" value="{{$option->id}}" hidden>

                                                                                                        <div class="form-group row">
                                                                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                                                                   for="textinput">الاسم </label>
                                                                                                            <div class="col-lg-12">
                                                                                                                <input name="name" type="text"
                                                                                                                       placeholder="الاسم "
                                                                                                                       value = "{{$option->name}}"
                                                                                                                       class="form-control btn-square" required
                                                                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <div class="form-group row">
                                                                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                                                                   for="textinput">السعر </label>
                                                                                                            <div class="col-lg-12">
                                                                                                                <input name="price" type="text"
                                                                                                                       placeholder="السعر "
                                                                                                                       value = "{{$option->price}}"
                                                                                                                       class="form-control btn-square" required
                                                                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                                                                            اغلاق
                                                                                                        </button>
                                                                                                        <button class="btn btn-primary">حفظ</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="modal animated fadeIn"
                                                                                         id="delete_option{{$option->id}}" tabindex="-1"
                                                                                         role="dialog" aria-labelledby="exampleModalLabel"
                                                                                         aria-hidden="true"
                                                                                         style="text-align:right">
                                                                                        <div class="modal-dialog modal-dialog-centered"
                                                                                             role="document">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header btn-danger">
                                                                                                    <h5 class="modal-title" id="exampleModalLabel">
                                                                                                        حذف الحقل</h5>
                                                                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                                                                    {{--                                            </button>--}}
                                                                                                </div>
                                                                                                <form method="post"
                                                                                                      action="{{route('deleteOption')}}"
                                                                                                      class="buttons">
                                                                                                    {{csrf_field()}}
                                                                                                    <div class="modal-body">
                                                                                                        <h4>هل انت متأكد ؟</h4>
                                                                                                        <h6>
                                                                                                            انت علي وشك حذف هذا الحقل
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <input type="hidden" name="model_id"
                                                                                                               value="{{$option->id}}">
                                                                                                        <button class="btn btn-dark" type="button"
                                                                                                                data-dismiss="modal">
                                                                                                            اغلاق
                                                                                                        </button>
                                                                                                        <button type="submit"
                                                                                                                class="btn btn-primary">تأكيد
                                                                                                        </button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-lg-1 alert alert-light">{{$variation->type == 0 ? "اختيار واحد" : "اختيار من متعدد"}}</div>
                                                                        <div class="col-lg-1 alert alert-light">
                                                                            @if($variation->required == 1)
                                                                                <i class='font-success show icon-check'></i>
                                                                            @else
                                                                                <i class="font-danger show icon-close"></i>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-lg-1 alert alert-light">
                                                                            <button title="اضافة اضافة" type="button"
                                                                                    class=" btn-primary"
                                                                                    data-toggle="modal"
                                                                                    data-target="#addOPtion{{$variation->id}}"
                                                                                    style="padding: 2px">
                                                                                <i class="fa fa-plus-circle"></i>
                                                                            </button>
                                                                            <button title="تعديل الاختيار" type="button" class=" btn-warning"
                                                                                    data-toggle="modal"
                                                                                    data-target="#edit_var{{$variation->id}}"
                                                                                    style="padding: 1px">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                            <button title="حذف الاختيار" type="button"
                                                                                    class=" btn-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#delete_var{{$variation->id}}"
                                                                                    style="padding: 2px">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal fade" id="addOPtion{{$variation->id}}" tabindex="-1" role="dialog"
                                                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">اضافة اضافة</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                                            aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <form class="form-horizontal needs-validation was-validated"
                                                                                      method="post" action="{{route('addOption')}}"
                                                                                      enctype="multipart/form-data">
                                                                                    {{csrf_field()}}
                                                                                    <div class="modal-body">

                                                                                        <input name="variation_id" value="{{$variation->id}}" hidden>

                                                                                        <div class="form-group row">
                                                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                                                   for="textinput">الاسم </label>
                                                                                            <div class="col-lg-12">
                                                                                                <input name="name" type="text"
                                                                                                       placeholder="الاسم "
                                                                                                       class="form-control btn-square" required
                                                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="form-group row">
                                                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                                                   for="textinput">السعر </label>
                                                                                            <div class="col-lg-12">
                                                                                                <input name="price" type="text"
                                                                                                       placeholder="السعر "
                                                                                                       class="form-control btn-square" required
                                                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>



                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                                                            اغلاق
                                                                                        </button>
                                                                                        <button class="btn btn-primary">حفظ</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </div>

                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="addVariation{{$c->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">اضافة اختيار</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal needs-validation was-validated"
                                                      method="post" action="{{route('addVariation')}}"
                                                      enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">

                                                        <input name="product_id" value="{{$c->id}}" hidden>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">الاسم </label>
                                                            <div class="col-lg-12">
                                                                <input name="name" type="text"
                                                                       placeholder="الاسم "
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-lg-1">
                                                                <input name="required" type="checkbox" class=" ">
                                                            </div>
                                                            <div class="col-lg-9">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput"> هذا الاختيار اجباري ؟ </label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">القائمة </label>
                                                            <div class="col-lg-12">
                                                                <select name="type" class="form-control btn-square">
                                                                    <option value="0">اختيار واحد</option>
                                                                    <option value="1">اختيار من متعدد</option>
                                                                </select>
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                            اغلاق
                                                        </button>
                                                        <button class="btn btn-primary">حفظ</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل منتج</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post"
                                                      action="{{route('editProduct')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="model_id" value="{{$c->id}}">


                                                        <div class="modal-body">


                                                            <div class="form-group row">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput">الاسم </label>
                                                                <div class="col-lg-12">
                                                                    <input id="name" name="name" value="{{$c->name}}"
                                                                           type="text" placeholder="الاسم "
                                                                           class="form-control btn-square" required
                                                                           oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله
                                                                        .
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput">الوصف </label>
                                                                <div class="col-lg-12">
                                                                    <textarea name="description"
                                                                              class="form-control btn-square"
                                                                              required>{{$c->description}}</textarea>
                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله
                                                                        .
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-lg-1">
                                                                    <input name="has_sizes"
                                                                           type="checkbox" {{$c->has_sizes == 1 ? "checked" : ""}} >
                                                                </div>
                                                                <div class="col-lg-9">
                                                                    <label class="col-lg-12 control-label text-lg-right"
                                                                           for="textinput"> المنتج له احجام ؟ </label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput">السعر (ادخل سعر اقل حجم اذا كان
                                                                    المنتج له احجام) </label>
                                                                <div class="col-lg-12">
                                                                    <input name="price_after" type="text"
                                                                           value="{{$c->price_after}}"
                                                                           placeholder="السعر "
                                                                           class="form-control btn-square" required
                                                                           oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله
                                                                        .
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput">القائمة </label>
                                                                <div class="col-lg-12">
                                                                    <select name="menu_id"
                                                                            class="form-control btn-square">
                                                                        @foreach($menus as $menu)
                                                                            <option
                                                                                    value="{{$menu->id}}" {{$menu->id == $c->menu_id ? 'selected' : ''}}>{{$menu->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله
                                                                        .
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-lg-12 control-label text-lg-right"
                                                                       for="textinput">الصورة</label>
                                                                <div class="col-lg-12">
                                                                    <input name="image" type="file"
                                                                           accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                                                                </div>
                                                                @if($c->image != "")
                                                                    <img src="{{$c->image}}" width="50px" height="50px">
                                                                @else
                                                                    -
                                                                @endif
                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="reset" class="btn btn-dark" data-dismiss="modal">
                                                            اغلاق
                                                        </button>
                                                        <button class="btn btn-primary" type="submit">تعديل</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal animated fadeIn" id="delete_{{$c->id}}" tabindex="-1"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"
                                         style="text-align:right">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header btn-danger">
                                                    <h5 class="modal-title" id="exampleModalLabel">حذف الحقل</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form method="post" action="{{route('deleteProduct')}}" class="buttons">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <h4>هل انت متأكد ؟</h4>
                                                        <h6>
                                                            انت علي وشك حذف هذا الحقل
                                                        </h6>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="model_id" value="{{$c->id}}">
                                                        <button class="btn btn-dark" type="button" data-dismiss="modal">
                                                            اغلاق
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">تأكيد</button>
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
                    </div>{{$countries->links()}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة منتج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post"
                      action="{{route('products.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم </label>
                            <div class="col-lg-12">
                                <input id="name" name="name" type="text" placeholder="الاسم "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف </label>
                            <div class="col-lg-12">
                                <textarea name="description" class="form-control btn-square" required></textarea>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-1">
                                <input name="has_sizes" type="checkbox" class=" "
                                       >
                            </div>
                            <div class="col-lg-9">
                                <label class="col-lg-12 control-label text-lg-right" for="textinput"> المنتج له احجام
                                    ؟ </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">السعر (ادخل سعر اقل حجم
                                اذا كان المنتج له احجام) </label>
                            <div class="col-lg-12">
                                <input name="price_after" type="text" placeholder="السعر "
                                       class="form-control btn-square"
                                       >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">القائمة </label>
                            <div class="col-lg-12">
                                <select name="menu_id" class="form-control btn-square">
                                    @foreach($menus as $menu)
                                        <option value="{{$menu->id}}">{{$menu->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                            <div class="col-lg-12">
                                <input name="image" type="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff" required>
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

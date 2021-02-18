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
                                نقاط المستخدمين المستبدلة
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            {{--                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>--}}
                            {{--                                اضافة عرض--}}
                            {{--                            </button>--}}
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
                                <th scope="col" style="text-align: center">العرض</th>
                                <th scope="col" style="text-align: center">المستخدم</th>
                                <th scope="col" style="text-align: center">الحالة</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($countries as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td style="text-align: center">
                                        @if($c->offer_point)
                                            @if($c->offer_point->image)
                                                <img src="{{$c->offer_point->image}}" width="50px" height="50px"><br>
                                            @endif
                                            <b>
                                                <span>الكود: </span>
                                                <span class="badge badge-dark">{{$c->offer_point->code}}</span>
                                            </b><br>
                                            <b>
                                                <span>النقاط: </span>
                                                <span class="badge badge-dark">{{$c->offer_point->points}}</span>
                                            </b><br>
                                            <b>
                                                <span>الوصف: </span>
                                                <span class="badge badge-dark">{{$c->offer_point->description}}</span>
                                            </b>
                                        @else
                                            <b> - </b>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if($c->user)
                                            <img src="{{$c->user->image}}" width="50px" height="50px"><br>
                                            <b>
                                                <span>الاسم: </span>
                                                <span class="badge badge-dark">{{$c->user->name}}</span>
                                            </b><br>
                                            <b>
                                                <span>الهاتف: </span>
                                                <span class="badge badge-dark">{{$c->user->phone}}</span>
                                            </b><br>
                                            <b>
                                                <span>الايميل: </span>
                                                <span class="badge badge-dark">{{$c->user->email}}</span>
                                            </b>

                                        @else
                                            <b> - </b>
                                        @endif
                                    </td>

                                    <td style="text-align: center">
                                        @if($c->status == 1)
                                            <b class="badge badge-success">تم الاستلام</b>
                                        @else
                                            <b class="badge badge-danger">لم يتم الاستلام</b>
                                        @endif
                                    </td>
                                    <td>
                                        {{--                                        @if($c->used != 1)--}}
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        @if($c->status == 1)
                                            <a href="{{route('editUserOfferPointStatus',$c->id)}}" >
                                                <button title="الغاء تاكيد الاستلام" class="btn btn-danger">
                                                    <i class="fa fa-minus-circle"></i>
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{route('editUserOfferPointStatus',$c->id)}}"  >
                                                <button title="تاكيد الاستلام" class="btn btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </a>
                                        @endif
                                        {{--@endif--}}
                                        {{--                                        @endif--}}

                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل العرض</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post"
                                                      action="{{route('editOfferPoint')}}"
                                                      enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="country_id" value="{{$c->id}}">


                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">الوصف</label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="description"
                                                                       value="{{$c->description}}" type="text"
                                                                       placeholder="الوصف"
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">الكود</label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="code" value="{{$c->code}}"
                                                                       type="text" placeholder="الكود "
                                                                       class="form-control btn-square" required
                                                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput"> النقاط</label>
                                                            <div class="col-lg-12">
                                                                <input id="name" name="points" value="{{$c->points}}"
                                                                       type="text" placeholder=" النقاط"
                                                                       class="form-control btn-square">
                                                            </div>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label class="col-lg-12 control-label text-lg-right"
                                                                   for="textinput">الصورة</label>
                                                            <input name="image" type="file"
                                                                   class="form-control btn-square">
                                                        </div>
                                                        @if($c->image != NULL)
                                                            <div class="form-group ">
                                                                <img src="{{  $c->image }}" width="60px" height="60px">
                                                            </div>
                                                            {{--@else
                                                                <div class="form-group ">
                                                                    <span>لا توجد صورة</span>
                                                                </div>--}}
                                                        @endif


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

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة العرض</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated"
                      method="post" action="{{route('offer_points.store')}}"
                      enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف</label>
                            <div class="col-lg-12">
                                <input id="name" name="description" type="text" placeholder="الوصف"
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الكود</label>
                            <div class="col-lg-12">
                                <input id="name" name="code" type="text" placeholder="الكود "
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النقاط</label>
                            <div class="col-lg-12">
                                <input id="name" name="points" type="text" placeholder=" النقاط"
                                       class="form-control btn-square" required
                                       oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                            <input name="image" type="file"
                                   class="form-control btn-square" {{--required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"--}}>
                            {{--<div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>--}}
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

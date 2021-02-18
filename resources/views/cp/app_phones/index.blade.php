@extends('cp.index')
@section('content')
    <div class="page-body" dir="rtl">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <div class="page-header-right">


                            <?php if(session()->has('insert_message')): ?>
                            <div class="alert alert-success dark alert-dismissible fade show col-lg-3" role="alert">
                                <i class="icon-thumb-up"></i>
                                <b>
                                    <?php echo e(session()->get('insert_message')); ?>
                                </b>
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close" >
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <?php endif; ?>

                            @if($errors->any())
                                <div class="alert alert-danger dark alert-dismissible fade show col-lg-3" role="alert">
                                    <i class="icon-thumb-down"></i>
                                    <b>
                                        @if ($errors)
                                            <?php echo "من فضلك اكمل ادخال البيانات المطلوبة !"; ?>
                                        @endif
                                    </b>
                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close" data-original-title="" title="">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif


                            <h3>
                                <i data-feather="home"></i>
                                ارقام التواصل
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاقسام</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
{{--                            @if(auth()->user()->hasPermissionTo('اضافة رقم للتواصل'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة رقم للتواصل
                            </button>
{{--                            @endif--}}
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
                                <th scope="col">الهاتف</th>
                                <th scope="col">الدولة </th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cats as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->phone}}</td>
                                    <td>
                                        @if($c->country)
                                            {{$c->country->name_ar}} / <br> {{$c->country->name_en}}
                                        @endif
                                    </td>

                                    <td>
{{--                                        @if(auth()->user()->hasPermissionTo('تعديل رقم للتواصل'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
{{--                                        @endif--}}
{{--                                        @if($c->active == 1)--}}
{{--                                            <a href="{{route('editCatStatus',$c->id)}}" >--}}
{{--                                                <button title="الغاء تفعيل" class="btn btn-danger">--}}
{{--                                                    ---}}
{{--                                                </button>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a href="{{route('editCatStatus',$c->id)}}"  >--}}
{{--                                                <button title="تفعيل" class="btn btn-success">--}}
{{--                                                    +--}}
{{--                                                </button>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}

                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل الرقم للتواصل</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('editAppPhone')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="AppPhone_id" value="{{$c->id}}">


                                                        <div class="form-group ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> رقم التواصل</label>
                                                            <div class="col-lg-12">
                                                                <input name="phone" type="text" placeholder=" رقم التواصل" value="{{$c->phone}}" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الدولة</label>
                                                            <div class="col-lg-12" >
                                                                <select class="form-control btn-square" name="country_id">
                                                                    @foreach($countries as $country)
                                                                        <option value="{{$country->id}}" {{$country->id == $c->country_id ? "selected" : ""}}>{{$country->name_en}} / {{$country->name_ar}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
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
                    </div>{{--{{$cats->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة رقم للتواصل</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('app_phones.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <div class="form-group ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> رقم التواصل</label>
                            <div class="col-lg-12">
                                <input name="phone" type="text" placeholder=" رقم التواصل" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> الدولة</label>
                            <div class="col-lg-12" >
                                <select class="form-control btn-square" required name="country_id">
                                    <option disabled selected>اختر الدولة</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}" >{{$country->name_en}} / {{$country->name_ar}}</option>
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

@endsection

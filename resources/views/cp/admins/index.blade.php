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
                                المشرفين
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المستخدمين</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة مشرف
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
                                <th scope="col"> الاسم </th>
                                {{--<th scope="col"> الصورة </th>--}}
                                {{--<th scope="col">الموبايل </th>--}}
                                <th scope="col"> البريد الالكتروني</th>
                                {{--<th scope="col"> المدينة </th>--}}
                                {{--<th scope="col"> الحالة </th>--}}
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="{{$c->suspend == 1 ? 'table-danger' :''}}">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->name}}</td>
                                    {{--@if($c->image != NULL)
                                        <th><img src="{{$c->image}}"  width="40px" height="40px"></th>
                                    @else
                                        <th> - </th>
                                    @endif--}}
                                    {{--<td>{{$c->phone}}</td>--}}
                                    <td>{{$c->email}}</td>
                                    {{--<td>{{$c->name_en}} / {{$c->name_ar}}</td>--}}
                                    {{--<td>
                                        @if($c->active == 1)
                                            <i class="font-success show icon-check"></i>
                                        @else
                                            <i class="font-danger show icon-close"></i>
                                        @endif
                                    </td>--}}
                                    <td>
                                        @if($c->suspend == 0)
                                            <a href="{{route('editClientStatus',$c->id)}}" >
                                                <button title="ايقاف " class="btn btn-danger">
                                                    <i class="fa fa-minus-circle"></i>
                                                </button>
                                            </a>
                                        @else
                                            <a href="{{route('editClientStatus',$c->id)}}" >
                                                <button title="اعادة تشغيل " class="btn btn-success">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </a>
                                        @endif
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل مشرف</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editCat')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="cat_id" value="{{$c->id}}">


                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالعربية</label>
                                                            <div class="col-lg-12">
                                                                <input name="name_ar" type="text" placeholder="الاسم بالعربية" class="form-control btn-square" value="{{$c->name_ar}}" required>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])

                                                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالنجليزية</label>
                                                            <div class="col-lg-12">
                                                                <input name="name_en" type="text" placeholder="الاسم بالنجليزية" class="form-control btn-square"  value="{{$c->name_en}}" required>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'en_name'])

                                                        <div class="form-group ">
                                                            <label  class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                                                            <input name="image" type="file" class="form-control">
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
                    </div>{{--{{$users->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة مشرف</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{ route('register') }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">


                        <div class="form-group ">
                            <label style="float: right">اسم العميل</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" autocomplete="name" autofocus placeholder="اسم العميل">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            {{--<input name="userName" type="text" class="form-control" placeholder="اسم العميل">--}}
                        </div>

                        <div class="form-group ">
                            <label style="float: right">البريد الالكتروني</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" autocomplete="email" placeholder="البريد الالكتروني">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            {{--<input name="userEmail" type="email" class="form-control" placeholder="البريد الالكتروني">--}}
                        </div>

                        <div class="form-group ">
                            <label style="float: right">كلمة المرور</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" autocomplete="new-password" placeholder="كلمة المرور">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            {{--<input name="userPassword" type="password" class="form-control" placeholder="كلمة المرور">--}}
                        </div>

                        <div class="form-group ">
                            <label style="float: right">تأكيد كلمة المرور</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')" autocomplete="new-password" placeholder="تأكيد كلمة المرور">
                            {{--<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="تأكيد كلمة المرور">--}}
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                            {{--<input name="userPassword" type="password" class="form-control" placeholder="كلمة المرور">--}}
                        </div>

                        {{--
                        <div class="form-group col-lg-6">
                            <label>image</label>
                            <input name="userImage" type="file" class="form-control">
                        </div>

                        <div class="form-group  col-lg-6">
                            <label>السمحيات</label>
                            <select name="userType"class="form-control" >
                                <option value="" disabled selected>اختر المستوي</option>
                                <option value="user" >User</option>
                                <option value="subadmin" >Subadmin</option>
                            </select>
                        </div>
                        --}}
                        <div class="clearfix"></div>

                        <div class="box-footer form-group">
                            {{--<button type="submit" class="btn btn-primary">حفظ</button>--}}
                            <button type="submit" class="btn btn-primary">
                            <!--{{ __('Register') }}-->
                                حفظ
                            </button>
                        </div>

                    </div>
                    <!-- /.box-body -->






                </form>
            </div>
        </div>
    </div>

@endsection

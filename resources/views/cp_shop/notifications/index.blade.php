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
                                الاشعارات
                                 ({{sizeof($nots)}})
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاشعارات</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة اشعار
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCatUser"><i class="icon-plus"></i>
                                اضافة اشعار لمستخدم
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCatDelegate"><i class="icon-plus"></i>
                                اضافة اشعار لمندوب
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCatDriver"><i class="icon-plus"></i>
                                اضافة اشعار لسائق
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
                                <th scope="col">العنوان </th>
                                <th scope="col"> النص </th>
                                <th scope="col"> اسم المستخدم</th>
                                <th scope="col"> نوع المستخدم</th>
                                <th scope="col"> تاريخ الانشاء </th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($nots as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->title}}</td>
                                    <td>{{$c->body}}</td>
                                    <td>
                                        @if($c->type == 0)
                                            {{$c->user->id}} <br>
                                            {{$c->user->name}} <br>
                                            {{$c->user->phone}} <br>
                                            {{$c->user->email}}
                                        @elseif($c->type == 1)
                                            {{$c->delegate->id}}
                                            {{$c->delegate->name}} <br>
                                            {{$c->delegate->phone}} <br>
                                            {{$c->delegate->email}}
                                        @elseif($c->type == 2)
                                            {{$c->driver->id}}
                                            {{$c->driver->name}} <br>
                                            {{$c->driver->phone}} <br>
                                            {{$c->driver->email}}
                                        @elseif($c->type == 3)
                                        @endif
                                    </td>
                                    <td>
                                        @if($c->type == 0)
                                            <b class="badge badge-info">عميل</b>
                                        @elseif($c->type == 1)
                                            <b class="badge badge-success">مندوب</b>
                                        @elseif($c->type == 2)
                                            <b class="badge badge-warning">سائق</b>
                                        @elseif($c->type == 3)
                                            <b class="badge badge-primary">الكل</b>
                                        @endif
                                    </td>
                                    <td>{{$c->created_at}}</td>
                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <a href="{{asset('/admin/notifications/delet/'.$c->id)}}">
                                        <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-minus-circle"></i>
                                        </button>
                                        </a>
                                        {{--@endif--}}
                                    </td>


                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$nots->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة اشعار</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('notifications.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> العنوان</label>
                            <div class="col-lg-12">
                                <input id="name" name="title" type="text" placeholder="العنوان" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النص</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="body"  placeholder="النص " dir="rtl" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"></textarea>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد الارسال</label>
                            <div class="col-lg-12">
                                <select name="send_to" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر </option>
                                    <option value="users"  >ارسال للمستخدمين فقط </option>
                                    <option value="drivers"  >ارسال للسائقين فقط </option>
                                    <option value="delegates"  >ارسال للمناديب فقط </option>
                                    <option value="all"  >ارسال للكل </option>
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

    <div class="modal fade" id="subCatUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة اشعار لعميل</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('notifications.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <input type="hidden" name="type" value="0">
                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> العنوان</label>
                            <div class="col-lg-12">
                                <input id="name" name="title" type="text" placeholder="العنوان" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النص</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="body"  placeholder="النص " dir="rtl" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"></textarea>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد الارسال</label>
                            <div class="col-lg-12">
                                <select name="user_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر </option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}"  >{{$user->name}} / {{$user->phone}} / {{$user->email}}</option>
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

    <div class="modal fade" id="subCatDelegate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة اشعار لمندوب</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('notifications.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <input type="hidden" name="type" value="1">
                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> العنوان</label>
                            <div class="col-lg-12">
                                <input id="name" name="title" type="text" placeholder="العنوان" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النص</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="body"  placeholder="النص " dir="rtl" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"></textarea>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد الارسال</label>
                            <div class="col-lg-12">
                                <select name="user_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر </option>
                                    @foreach($delegates as $delegate)
                                        <option value="{{$delegate->id}}"  >{{$delegate->f_name}} / {{$delegate->phone}} / {{$delegate->email}}</option>
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

    <div class="modal fade" id="subCatDriver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة اشعار لسائق</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('notifications.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <input type="hidden" name="type" value="2">
                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> العنوان</label>
                            <div class="col-lg-12">
                                <input id="name" name="title" type="text" placeholder="العنوان" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> النص</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="body"  placeholder="النص " dir="rtl" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"></textarea>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد الارسال</label>
                            <div class="col-lg-12">
                                <select name="user_id" class="btn form-control b-light digits" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                    <option value="" selected disabled>اختر </option>
                                    @foreach($drivers as $driver)
                                        <option value="{{$driver->id}}"  >{{$driver->f_name}} / {{$driver->phone}} / {{$driver->email}}</option>
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

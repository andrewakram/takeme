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
                                <button class="close" type="button" data-dismiss="alert" aria-label="Close">
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
                                    <button class="close" type="button" data-dismiss="alert" aria-label="Close"
                                            data-original-title="" title="">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            @endif


                            <h3>
                                <i data-feather="home"></i>
                                المتاجر
                                ({{$type}})
{{--                                @if(auth()->user()->hasPermissionTo('المتاجر'))--}}
{{--                                    <a href="{{asset('/shopss')}}"--}}
{{--                                       class="btn btn-primary"><span>المتاجر قيد التنفيذ</span></a>--}}
                                    <a href="{{asset('/admin/active-shops')}}"
                                       class="btn btn-success"><span>المتاجر المفعلة</span></a>
                                    <a href="{{asset('/admin/inactive-shops')}}" class="btn btn-danger"><span>المتاجر الغير المفعلة</span></a>
{{--                                @endif--}}
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المتاجر</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--                            @if(auth()->user()->hasPermissionTo('اضافة باقة'))--}}
                            <a type="button" class="btn btn-primary"
{{--                                    data-toggle="modal"--}}
{{--                                    data-target="#subCat"--}}
                                    href="{{route('createShop')}}"
                            >
                                <i class="icon-plus"></i>
                                اضافة متجر
                            </a>
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
                                <th scope="col">تفعيل/ايقاف</th>
                                <th scope="col">مضمون</th>
                                <th scope="col"> الاسم</th>
                                <th scope="col"> الصورة</th>
                                <th scope="col">صورة الخلفية</th>
                                <th scope="col">الموبايل</th>
                                <th scope="col"> البريد الالكتروني</th>
                                <th scope="col"> الاحداثيات</th>
                                <th scope="col"> الوصف</th>
{{--                                <th scope="col"> العضوية</th>--}}
{{--                                <th scope="col"> website</th>--}}
{{--                                <th scope="col"> السجل التجاري</th>--}}
{{--                                <th scope="col"> الرقم الضريبي</th>--}}
{{--                                <th scope="col"> رخصة التسجيل</th>--}}
{{--                                <th scope="col"> الصورة الضريبية</th>--}}
{{--                                <th scope="col">صورة السجل التجاري</th>--}}
{{--                                <th scope="col"> المواعيد</th>--}}
                                <th scope="col"> القسم التابع له</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $c)
                                <tr id="main_cat_{{$c->id}}"
                                    class="{{$c->suspend == 1 ? 'table-danger' :''}}">
                                    <td>{{$c->id}}</td>
                                    <td>
{{--                                        @if(auth()->user()->hasPermissionTo('تعديل اعلان'))--}}
                                            <div class="nav-right col p-0">
                                                <div class="media">
                                                    <div class="media-body text-right icon-state switch-outline">
                                                        <label class="switch">
                                                            <input type="checkbox" id="status" model_id="{{$c->id}}"
                                                                   @if($c->suspend == 0)  checked @endif><span
                                                                    class="switch-state bg-success"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
{{--                                        @endif--}}
                                    </td>
                                    <td>
                                        {{--                                        @if(auth()->user()->hasPermissionTo('تعديل اعلان'))--}}
                                        <div class="nav-right col p-0">
                                            <div class="media">
                                                <div class="media-body text-right icon-state switch-outline">
                                                    <label class="switch">
                                                        <input type="checkbox" id="verified" model_id="{{$c->id}}"
                                                               @if($c->verified == 1)  checked @endif><span
                                                                class="switch-state bg-success"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        {{--                                        @endif--}}
                                    </td>
                                    <td>{{$c->name}}</td>
                                    @if($c->image)
                                        <td>
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->image}}" width="50px" height="50px"></img>
                                            </button>

                                            {{--==image==--}}
                                            <div class="modal fade" id="image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--==image==--}}
                                        </td>
                                    @else
                                        <th> -</th>
                                    @endif
                                    @if($c->cover_image)
                                        <td>
                                            <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#cover_image{{$c->id}}" style="padding: 1px">
                                                <img src="{{$c->cover_image}}" width="50px" height="50px"></img>
                                            </button>

                                            {{--==image==--}}
                                            <div class="modal fade" id="cover_image{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <img src="{{$c->cover_image}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            {{--==image==--}}
                                        </td>
                                    @else
                                        <th> -</th>
                                    @endif
                                    <td>{{$c->phone}}</td>
                                    <td>
                                        {{$c->email}} <br>
                                        <b class="badge badge-info">{{$c->country->name}}</b>
                                    </td>
                                    <td>
                                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$c->lat}},{{$c->lng}}">
                                            <i class="icon-location-pin" style="font-size: x-large"></i>
                                        </a>
                                    </td>
                                    <td>{{$c->description}}</td>

                                    <td>
                                        <b class="badge badge-dark">
                                            {{isset($c->category->name) ? $c->category->name : "-"}}
                                        </b>
                                    </td>

                                    <td>
                                        <a title="تعديل" type="button" class="btn btn-warning"
                                                style="margin: 1px"
                                                href="{{ asset('admin/shops/edit/'.$c->id) }}"
{{--                                                data-toggle="modal"--}}
{{--                                                data-target="#edit_{{$c->id}}"--}}
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal"
                                                style="margin: 1px"
                                                data-target="#delete_{{$c->id}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>


                                    {{--///////////////////////////--}}
                                    <div class="modal animated fadeIn" id="delete_{{$c->id}}" tabindex="-1"
                                         style="text-align: right"
                                         role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header btn-danger">
                                                    <h5 class="modal-title" id="exampleModalLabel">حذف الامتحان</h5>
                                                    {{--                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                                    {{--                                                <span aria-hidden="true">&times;</span>--}}
                                                    {{--                                            </button>--}}
                                                </div>
                                                <form method="post" action="{{route('deleteShop')}}" class="buttons">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <h4>هل انت متأكد ؟</h4>
                                                        <h6>
                                                            انت علي وشك حذف المتجر
                                                            <br>رقم المتجر: ({{$c->id}})
                                                            <br>الاسم: ({{$c->name}})

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
                    </div>{{--{{$users->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).on('change', '#status', function (e) {

            var model_id = $(this).attr('model_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('editShopStatus')}}",
                data: {
                    model_id: model_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    location.reload();
                    if (response.success) {
                        toastr.success(response.success);
                    } else if (response.warning) {
                        toastr.warning(response.warning);
                    } else {
                        toastr.error(response.error);
                    }
                },
                error: function (jqXHR) {
                    toastr.error(jqXHR.responseJSON.message);
                }
            });
        });

        $(document).on('change', '#verified', function (e) {

            var model_id = $(this).attr('model_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('editShopVerified')}}",
                data: {
                    model_id: model_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    location.reload();
                    if (response.success) {
                        toastr.success(response.success);
                    } else if (response.warning) {
                        toastr.warning(response.warning);
                    } else {
                        toastr.error(response.error);
                    }
                },
                error: function (jqXHR) {
                    toastr.error(jqXHR.responseJSON.message);
                }
            });
        });

    </script>


@endsection

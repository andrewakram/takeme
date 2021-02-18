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
                                الايصالات
                                    <a href="{{asset('receipts')}}" class="btn btn-success"><span>الايصالات المفعلة</span></a>
                                    <a href="{{asset('inactive-receipts')}}" class="btn btn-danger"><span>الايصالات الغير المفعلة</span></a>

                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاقسام</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
{{--                            @if(auth()->user()->hasPermissionTo('اضافة حساب بنكي'))--}}
{{--                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>--}}
{{--                                اضافة حساب بنكي--}}
{{--                            </button>--}}
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
                                <th scope="col" style="text-align: right;padding-right: 3%">المستخدم</th>
                                <th scope="col">صورة الايصال </th>
                                <th scope="col"> الوصف</th>
                                <th scope="col"> التاريخ</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cats as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="">
                                    <td>{{$c->id}}</td>
                                    <td style="text-align: right;padding-right: 3%">
                                        @if($c->user)
                                            <b>الاسم: </b> <b class="badge badge-success" style="font-size: medium;margin: 1px"> {{$c->user->name}}</b> <br>
                                            <b>االايميل: </b> <b class="badge badge-success" style="font-size: medium;margin: 1px"> {{$c->user->email}}</b> <br>
                                            <b>الهاتف: </b> <b class="badge badge-success" style="font-size: medium;margin: 1px"> {{$c->user->phone}}</b>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <button title="عرض" type="button" class="btn btn-success" data-toggle="modal" data-target="#image{{$c->id}}">
                                            <img src="{{$c->image}}"  width="40px" height="40px">
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
                                    <td>{{$c->description}}</td>
                                    <td>
                                        {{$c->created_at}}
                                    </td>

                                    <td>
{{--                                        @if(auth()->user()->hasPermissionTo('تعديل حساب بنكي'))--}}
                                        <div class="nav-right col p-0">
                                            <div class="media">
                                                <div class="media-body text-right icon-state switch-outline">
                                                    <label class="switch">
                                                        <input type="checkbox" id="status" model_id="{{$c->id}}"
                                                               @if($c->active == 1)  checked @endif><span
                                                                class="switch-state bg-success"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
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

                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>
{{--                    {{$cats->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).on('change','#status', function(e) {

            var model_id = $(this).attr('model_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('editStatus')}}",
                data: {
                    model_id: model_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response){
                    location.reload();
                    if (response.success){
                        toastr.success(response.success);
                    }else if(response.warning){
                        toastr.warning(response.warning);
                    }else{
                        toastr.error(response.error);
                    }
                },
                error: function(jqXHR){
                    toastr.error(jqXHR.responseJSON.message);
                }
            });
        });
    </script>


@endsection

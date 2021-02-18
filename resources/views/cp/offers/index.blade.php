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
                                الاعلانات
                                ({{sizeof($offers)}})
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاعلانات</li>
                            </ol>--}}
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
                                <th scope="col">قبول</th>
                                <th scope="col">الاسم بالعربية</th>
                                <th scope="col">الاسم بالانجليزية</th>
                                <th scope="col"> السعر القديم</th>
                                <th scope="col"> السعر الجديد</th>
                                <th scope="col"> الوصف</th>
                                <th scope="col"> الصورة</th>
                                <th scope="col"> اسم المتجر</th>
                                <th scope="col"> تاريخ انشاء الاعلان</th>
                                {{--                                <th scope="col">الاختيارات</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($offers as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>
                                        @if(auth()->user()->hasPermissionTo('تعديل اعلان'))
                                            <div class="nav-right col p-0">
                                                <div class="media">
                                                    <div class="media-body text-right icon-state switch-outline">
                                                        <label class="switch">
                                                            <input type="checkbox" id="status" model_id="{{$c->id}}"
                                                                   @if($c->accept == 1)  checked @endif><span
                                                                class="switch-state bg-success"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{$c->name_ar}}</td>
                                    <td>{{$c->name_en}}</td>
                                    <td>{{$c->old_price}}</td>
                                    <td>{{$c->new_price}}</td>
                                    <td>{{$c->description_en}} / {{$c->description_ar}}</td>
                                    @if($c->image != NULL)
                                        <th>
                                            <img src="{{$c->image}}" width="50px" height="50px">
                                            <a href="{{$c->image}}" target="_blank" class="btn btn-primary">
                                                <b>عرض</b>
                                            </a>
                                        </th>

                                    @else
                                        <th> -</th>
                                    @endif
                                    <td>{{$c->name}}</td>
                                    <td>{{$c->created_at}}</td>
                                    {{--                                    <td>--}}
                                    {{--                                        @if(auth()->user()->hasPermissionTo('تعديل اعلان'))--}}
                                    {{--                                        --}}{{--<button title="اخفاء" type="button" class="btn btn-danger" data-toggle="modal" data-target="#edit_{{$c->id}}">--}}
                                    {{--                                            ---}}
                                    {{--                                        </button>--}}

                                    {{--                                        @if($c->active == 1)--}}
                                    {{--                                            <a href="{{route('editOfferStatus',$c->id)}}" >--}}
                                    {{--                                                <button title="الغاء تفعيل" class="btn btn-danger">--}}
                                    {{--                                                    ---}}
                                    {{--                                                </button>--}}
                                    {{--                                            </a>--}}
                                    {{--                                        @else--}}
                                    {{--                                            <a href="{{route('editOfferStatus',$c->id)}}"  >--}}
                                    {{--                                                <button title="تفعيل" class="btn btn-success">--}}
                                    {{--                                                    +--}}
                                    {{--                                                </button>--}}
                                    {{--                                            </a>--}}
                                    {{--                                        @endif--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </td>--}}


                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$offers->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


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
                url: "{{URL::route('acceptOffer')}}",
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

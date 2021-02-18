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
                                الباقات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاقسام</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
{{--                            @if(auth()->user()->hasPermissionTo('اضافة باقة'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة باقة
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
                                <th scope="col">الباقة مجانية الان</th>
                                <th scope="col">الصورة</th>
                                <th scope="col">الاسم </th>
                                <th scope="col">الوصف</th>
                                <th scope="col"> مدة الباقة بالايام</th>
                                <th scope="col">سعر الباقة</th>
                                <th scope="col">عدد صور الباقة</th>
                                <th scope="col">عدد فيديوهات الباقة</th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cats as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="">
                                    <td>{{$c->id}}</td>
                                    <td>
                                        <div class="nav-right col p-0">
                                            <div class="media">
                                                <div class="media-body text-right icon-state switch-outline">
                                                    <label class="switch">
                                                        <input type="checkbox" id="status" model_id="{{$c->id}}"
                                                               @if($c->is_free == 1)  checked @endif><span
                                                            class="switch-state bg-success"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if($c->image != NULL)
                                        <th><img src="{{$c->image}}"  width="50px" height="50px"></th>
                                    @else
                                        <th> - </th>
                                    @endif
                                    <td>{{$c->name_ar}} / <br> {{$c->name_en}} </td>
                                    <td>{{$c->description_ar}} / <br> {{$c->description_en}} </td>
                                    <td>{{$c->period}} &nbsp;<span class="badge badge-primary">يوم</span></td>
                                    <td>{{$c->price}} &nbsp;<span class="badge badge-success">$</span></td>
                                    <td>{{$c->no_of_images}}</td>
                                    <td>{{$c->no_of_videos}}</td>

                                    <td>
                                        @if(auth()->user()->hasPermissionTo('تعديل باقة'))
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        @endif
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
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل الباقة</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('editMembership')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="membership_id" value="{{$c->id}}">


                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الاسم بالعربية</label>
                                                            <div class="col-lg-12">
                                                                <input id="inputName1" name="name_ar" type="text" placeholder="الاسم بالعربية" class="form-control btn-square" for="textinput" value="{{$c->name_ar}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])

                                                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالنجليزية</label>
                                                            <div class="col-lg-12">
                                                                <input name="name_en" type="text" placeholder="الاسم بالنجليزية" class="form-control btn-square" for="textinput" value="{{$c->name_en}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'en_name'])

                                                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الوصف بالعربية</label>
                                                            <div class="col-lg-12">
                                                                <input id="inputName1" name="description_ar" type="text" placeholder="الوصف بالعربية" class="form-control btn-square" for="textinput" value="{{$c->description_ar}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'ar_name'])

                                                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف بالنجليزية</label>
                                                            <div class="col-lg-12">
                                                                <input name="description_en" type="text" placeholder="الوصف بالنجليزية" class="form-control btn-square" for="textinput" value="{{$c->description_en}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>
                                                        @include('cp.layouts.error', ['input' => 'en_name'])

                                                        <div class="form-group row" >
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">امدة الباقة بالايام</label>
                                                            <div class="col-lg-12">
                                                                <input name="period" type="number" placeholder="مدة الباقة بالايام" class="form-control btn-square" for="textinput" value="{{$c->period}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">سعر الباقة </label>
                                                            <div class="col-lg-12">
                                                                <input name="price" type="number" placeholder="سعر الباقة " class="form-control btn-square" for="textinput" value="{{$c->price}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">عدد صور الباقة</label>
                                                            <div class="col-lg-12">
                                                                <input name="no_of_images" type="number" placeholder="عدد صور الباقة" class="form-control btn-square" for="textinput" value="{{$c->no_of_images}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> عدد فيديوهات الباقة </label>
                                                            <div class="col-lg-12">
                                                                <input name="no_of_videos" type="number" placeholder="عدد فيديوهات الباقة" class="form-control btn-square" for="textinput" value="{{$c->no_of_videos}}" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label  class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                                                            <input name="image" type="file" class="form-control btn-square" >
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
                    <h5 class="modal-title" id="exampleModalLabel">اضافة باقة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('memberships.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">

                        <div class="form-group ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالعربية</label>
                            <div class="col-lg-12">
                                <input name="name_ar" type="text" placeholder="الاسم بالعربية" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم بالانجليزية</label>
                            <div class="col-lg-12" >
                                <input name="name_en" type="text" placeholder="الاسم بالانجليزية" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الوصف بالعربية</label>
                            <div class="col-lg-12">
                                <input id="inputName1" name="description_ar" type="text" placeholder="الوصف بالعربية" class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الوصف بالنجليزية</label>
                            <div class="col-lg-12">
                                <input name="description_en" type="text" placeholder="الوصف بالنجليزية" class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">امدة الباقة بالايام</label>
                            <div class="col-lg-12">
                                <input name="period" type="number" placeholder="مدة الباقة بالايام" class="form-control btn-square" for="textinput"   required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">سعر الباقة </label>
                            <div class="col-lg-12">
                                <input name="price" type="number" placeholder="سعر الباقة " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">عدد صور الباقة</label>
                            <div class="col-lg-12">
                                <input name="no_of_images" type="number" placeholder="عدد صور الباقة" class="form-control btn-square" for="textinput" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> عدد فيديوهات الباقة </label>
                            <div class="col-lg-12">
                                <input name="no_of_videos" type="number" placeholder="عدد فيديوهات الباقة" class="form-control btn-square" for="textinput" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group " >
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                            <input name="image" type="file" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                            <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
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


    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        $(document).on('change','#status', function(e) {
console.log('dgd');
            var model_id = $(this).attr('model_id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{URL::route('membershipEditStatus')}}",
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

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
                                اضافة متجر
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">المتاجر</li>
                            </ol>--}}
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('shops.store')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-body">



                <div class="form-group row">
                    <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم </label>
                    <div class="col-lg-12">
                        <input name="name" type="text" placeholder="الاسم" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>


                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الوصف </label>
                    <div class="col-lg-12">
                        <input id="inputName1" name="description" type="text" placeholder="الوصف " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>

                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الموبايل </label>
                    <div class="col-lg-12">
                        <input id="inputName1" name="phone" type="text" placeholder="الموبايل " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">البريد الالكتروني </label>
                    <div class="col-lg-12">
                        <input id="inputName1" name="email" type="text" placeholder="البريد الالكتروني " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>
                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">كلمة المرور </label>
                    <div class="col-lg-12">
                        <input id="inputName1" name="password" type="password" placeholder="كلمة المرور " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>

                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">مضمون / غير مضمون</label>
                    <div class="col-lg-12">
                        <select class="form-control btn-square" name="category_id">
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>

                <div class="form-group row ">
                    <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">مضمون / غير مضمون</label>
                    <div class="col-lg-12">
                        <select class="form-control btn-square" name="verified">
                            <option value="1">مضمون</option>
                            <option value="0">غير مضمون</option>
                        </select>
                        <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                    </div>
                </div>

                <div class="form-group " >
                    <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                    <input name="image" type="file" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                </div>

                <div class="form-group " >
                    <label class="col-lg-12 control-label text-lg-right" for="textinput">صورة الخلفية</label>
                    <input name="cover_image" type="file" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                    <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                </div>

                {{--                            //////////////////////////////////////////////--}}

                <div class="pure-u-1 pure-u-md-1 pure-u-lg-1 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <input  type="hidden" class="form-control"  name="lng" value="" id="userlat" placeholder="Enter lat ">
                    </div>
                    <div class="form-group">
                        <input  type="hidden" class="form-control"  name="lat" value="" id="userlng" placeholder="Enter long">
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div id="userlocation" style="width:100%;height:350px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="autocomplete">البحث عن عنوان</label>
                                <input type="text" class="form-control" id="autocomplete" placeholder="البحث عن عنوان" >
                            </div>
                        </div>
                    </div>
                </div>

                {{--                            //////////////////////////////////////////////--}}
                <br>


            </div>
            <div class="modal-footer">
                
                <button class="btn btn-primary">حفظ</button>
            </div>
        </form>
        <!-- Container-fluid Ends-->
    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة متجر</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('shops.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">



                        <div class="form-group row">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الاسم </label>
                            <div class="col-lg-12">
                                <input name="name" type="text" placeholder="الاسم" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>


                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الوصف </label>
                            <div class="col-lg-12">
                                <input id="inputName1" name="description" type="text" placeholder="الوصف " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">الموبايل </label>
                            <div class="col-lg-12">
                                <input id="inputName1" name="phone" type="text" placeholder="الموبايل " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">البريد الالكتروني </label>
                            <div class="col-lg-12">
                                <input id="inputName1" name="email" type="text" placeholder="البريد الالكتروني " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">كلمة المرور </label>
                            <div class="col-lg-12">
                                <input id="inputName1" name="password" type="password" placeholder="كلمة المرور " class="form-control btn-square" for="textinput"  required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">مضمون / غير مضمون</label>
                            <div class="col-lg-12">
                                <select class="form-control btn-square" name="category_id">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right"  for="validationCustom04">مضمون / غير مضمون</label>
                            <div class="col-lg-12">
                                <select class="form-control btn-square" name="verified">
                                    <option value="1">مضمون</option>
                                    <option value="0">غير مضمون</option>
                                </select>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>

                        <div class="form-group " >
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                            <input name="image" type="file" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                            <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                        </div>

                        <div class="form-group " >
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">صورة الخلفية</label>
                            <input name="cover_image" type="file" class="form-control btn-square" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                            <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                        </div>

                        {{--                            //////////////////////////////////////////////--}}

                        <div class="pure-u-1 pure-u-md-1 pure-u-lg-1 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input  type="hidden" class="form-control"  name="lng" value="" id="userlat" placeholder="Enter lat ">
                            </div>
                            <div class="form-group">
                                <input  type="hidden" class="form-control"  name="lat" value="" id="userlng" placeholder="Enter long">
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <div id="userlocation" style="width:100%;height:350px"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="autocomplete">البحث عن عنوان</label>
                                        <input type="text" class="form-control" id="autocomplete" placeholder="البحث عن عنوان" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--                            //////////////////////////////////////////////--}}
                        <br>


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
        $(document).on('change', '#status', function (e) {
            alert('ww');
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

    </script>

    <script>
        var marker = null;
        var placeSearch, autocomplete;
        function initMap() {
            autocomplete =
                new google.maps.places.Autocomplete((document.getElementById('autocomplete')),
                    {types: ['geocode']});
            var map = new google.maps.Map(document.getElementById('userlocation'), {
                zoom: 7,
                center: {lat: 26.719517, lng: 29.2161655 }
            });
            var MaekerPos = new google.maps.LatLng(0 , 0);
            marker = new google.maps.Marker({
                position: MaekerPos,
                map: map
            });
            autocomplete.addListener('place_changed', function(){
                placeMarkerAndPanTo(autocomplete.getPlace().geometry.location, map);
                document.getElementById("userlat").value=autocomplete.getPlace().geometry.location.lat();
                document.getElementById("userlng").value=autocomplete.getPlace().geometry.location.lng();
            });
            map.addListener('click', function(e) {
                placeMarkerAndPanTo(e.latLng, map);
                document.getElementById("userlat").value=e.latLng.lat();
                document.getElementById("userlng").value=e.latLng.lng();
            });
        }
        function placeMarkerAndPanTo(latLng, map) {
            map.setZoom(9);
            marker.setPosition(latLng);
            map.panTo(latLng);
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHQj-nrlglAjt4sQgpRNcOdbRGNm7eGMc&libraries=places&callback=initMap">
    </script>


@endsection

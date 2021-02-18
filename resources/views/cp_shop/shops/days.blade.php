@extends('cp_shop.index')
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ url('cp/endless/assets/css/timepicker.css') }}">
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
                                 مواعيد عمل المتجر
                            </h3>
                                @foreach($shop as $shp)
                                <div class="form-group row">


                                    <div class="col-lg-4">
                                        <input  type="text" disabled value="{{isset($shp->day) ? $shp->day : ""}}"
                                               class="form-control btn-square"
                                               >
                                    </div>
                                    <div class="col-lg-4">
                                        <input  type="text" disabled value="{{$shp->from}}"
                                               class="form-control btn-square"
                                        >
                                    </div>
                                    <div class="col-lg-4">
                                        <input  type="text" disabled value="{{$shp->to}}"
                                               class="form-control btn-square"
                                        >
                                    </div>
                                </div>
                                @endforeach
                                <hr>
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
        <form class="form-horizontal" method="post"
              action="{{route('updateDailyWork')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="modal-body">

                <h3 style="text-align: right">
                    <i data-feather="home"></i>
                    تعديل مواعيد عمل المتجر
                </h3>
                @foreach($days as $day)
                    <div class="form-group row">
                        <div class="col-lg-1">
                            <input name="day_id[]" type="checkbox" value="{{$day->id}}"
                                   @foreach($shop as $s) {{$s->day_id == $day->id ? "checked" : ""}} @endforeach class="form-control btn-square">

                        </div>

                        <div class="col-lg-3">
                            <input name="day_name" type="text" disabled value="{{$day->name}}"
                                   class="form-control btn-square" required
                                   oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">
                            <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                        </div>

                        <div class="col-lg-4">
                            <div class="input-group clockpicker ">
                                <input class="form-control" name="from[]" type="time"
                                       value="@foreach($shop as $s) {{$s->day_id == $day->id ? $s->from : ""}}@endforeach"
                                        data-original-title="" title=""><span class="input-group-addon"><span
                                        class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="input-group clockpicker ">
                                <input class="form-control" name="to[]" type="time"
                                       value="@foreach($shop as $s) {{$s->day_id == $day->id ? $s->to : ""}}@endforeach"
                                        data-original-title="" title=""><span class="input-group-addon"><span
                                        class="glyphicon glyphicon-time"></span></span>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>

            <div class="modal-footer">
                <button class="btn btn-primary mr-1" type="submit">تعديل
                </button>
            </div>
        </form>
        <!-- Container-fluid Ends-->
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
    <script src="{{ url('cp/endless/assets/js/form-validation-custom.js') }}"></script>
    <script src="{{ url('cp/endless/assets/js/time-picker/jquery-clockpicker.min.js') }}"></script>
    <script src="{{ url('cp/endless/assets/js/time-picker/highlight.min.js') }}"></script>
    <script src="{{ url('cp/endless/assets/js/time-picker/clockpicker.js') }}"></script>


@endsection


<div class="card-body" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
    <form class="form-horizontal" action="{{asset(Session::get("lang").'/admin/search')}}" method="get">
        <div class="form-row">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{isset($search) ? $search : ''}}" placeholder="{{ trans('admin.searchWorkerUser')}}" />
                <button class="btn btn-primary">{{ trans('admin.searchNow')}}</button>
            </div>
        </div><br>
        <div class="form-row">
            <div class="col-md-6 mb-3" >
                <label for="validationCustom01">{{ trans('admin.from')}}</label>
                <input type="date" class="form-control" name="from" value="{{request('from')}}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="validationCustom02">{{ trans('admin.to')}}</label>
                <input type="date" class="form-control" name="to" value="{{request('to')}}">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 mb-3">
                <select class="form-control select" id="main_cats" name="main_cats" required>
                    <option selected disabled>{{ trans('admin.selectMainCategory')}}</option>
                    @foreach($cats as $cat)
                        <option value="{{$cat->id}}">{{$cat->en_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <select class="form-control" name="sub_cats" data-style="btn-success" id="sub_cats">
                    <option selected disabled>{{ trans('admin.selectCategory')}}</option>
                </select>
            </div>
            <div class="col-md-8 mb-3">
                <select class="form-control" name="service_type">
                    <option selected disabled>{{ trans('admin.selectServiceType')}}</option>
                    <option value="0">{{ trans('admin.open')}}</option>
                    <option value="1">{{ trans('admin.completed')}}</option>
                    <option value="2">{{ trans('admin.cancelled')}}</option>
                    <option value="3">{{ trans('admin.rejected')}}</option>
                </select>
            </div>
        </div>
    </form>
</div>

<script src="{{asset('admin/assets/js/jquery-3.2.1.min.js')}}"></script>
<script>
    $('#main_cats').on('change', function (e) {
        var parent_id = $('#main_cats').val();
        if (parent_id) {
            $.ajax({
                url:"{{url('en/admin/get_sub_category/')}}/"+parent_id,
                /*url: 'en/admin/get_sub_category/'+parent_id,*/
                type: "GET",

                dataType: "json",

                success: function (data) {
                    console.log(data);

                    $('#sub_cats').empty();
                    $('#sub_cats').append('<option selected disabled> Select a Sub Category </option>');
                    $.each(data, function (i, sub_cat) {
                        $('#sub_cats').append('<option value="' + sub_cat.id + '">' + sub_cat.en_name + '-' + sub_cat.ar_name +'</option>');
                    });
                }
            });

        }
    });
</script>

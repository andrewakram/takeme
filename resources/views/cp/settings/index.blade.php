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
                                الاعدادات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">عن التطبيق</li>
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
                                {{--<th scope="col">#</th>--}}
                                <th scope="col">نسبة التطبيق</th>
                                <th scope="col">نسبة الضريبة</th>

                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>

                                    <tr id="" >


                                        <td>
                                            <b class="badge badge-dark" style="font-size: large">{{$app_percent}} %</b>
                                        </td>

                                        <td>
                                            <b class="badge badge-dark" style="font-size: large">{{$fee_percent}} %</b>
                                        </td>

                                        <td>
                                            {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                            <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_setting">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            {{--@endif--}}
                                        </td>

                                        <div class="modal fade" id="edit_setting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل عن التطبيق</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form class="form-horizontal" method="post" action="{{route('edit_settings')}}" enctype="multipart/form-data">
                                                        {{csrf_field()}}
                                                        <div class="modal-body">



                                                            <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                                <label class="col-lg-12 control-label text-lg-right" for="textinput"> نسبة التطبيق</label>
                                                                <div class="col-lg-12">
                                                                    <input class="form-control" name="app_percent" value="{{$app_percent}}" placeholder=" " required >
                                                                </div>
                                                            </div>

                                                            <div class="form-group row {{ $errors->has('ar_name') ? ' has-error' : '' }}">
                                                                <label class="col-lg-12 control-label text-lg-right" for="textinput">نسبة الضريبة</label>
                                                                <div class="col-lg-12">
                                                                    <input class="form-control" name="app_percent" value="{{$fee_percent}}" placeholder=" " required >
                                                                </div>
                                                            </div>


                                                            {{--<div class="form-group row {{ $errors->has('en_name') ? ' has-error' : '' }}">
                                                                <label class="col-lg-12 control-label text-lg-right" for="textinput">النص بالنجليزية</label>
                                                                <div class="col-lg-12">
                                                                    <textarea class="form-control" name="body_en" rows="10" placeholder="النص بالنجليزية" required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')">{{$c->body_en}}</textarea>
                                                                </div>
                                                            </div>
                                                            @include('cp.layouts.error', ['input' => 'en_name'])--}}






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

                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>


@endsection

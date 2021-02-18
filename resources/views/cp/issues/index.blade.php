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
                                المشكلات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة النص
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

                                <th scope="col"> النص بالعربية </th>
                                <th scope="col"> النص بالانجليزية </th>
                                <th scope="col">التحديد</th>

                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>

                                    <td>{{$c->ar_issue}}</td>
                                    <td>{{$c->en_issue}}</td>
                                    <td>{{$c->is_captin == 1 ? "خاص بالسائق" : "خاص بالمستخدم"}}</td>

                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {{--@endif--}}

                                        <a href="{{route('deleteIssue',$c->id)}}" data-original-title="" title="">
                                            <button title="" class="btn btn-danger" data-original-title="حذف">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                        </a>
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل النص</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editIssue')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="issue_id" value="{{$c->id}}">


                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">النص بالعربية</label>
                                                            <div class="col-lg-12">
                                                                <input name="ar_issue" class="form-control" type="text" value="{{$c->ar_issue}}" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">النص بالانجليزية</label>
                                                            <div class="col-lg-12">
                                                                <input name="en_issue" class="form-control" type="text" value="{{$c->en_issue}}" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد</label>
                                                            <div class="col-lg-12">
                                                                <select name="is_captin" class="form-control digits" id="exampleFormControlSelect9">
                                                                    <option value="0" {{$c->is_captin == 0 ? "selected" : ""}}>خاص بالمستخدم</option>
                                                                    <option value="1" {{$c->is_captin == 1 ? "selected" : ""}}>خاص بالسائق</option>
                                                                </select>
                                                            </div>
                                                        </div>


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
                    </div>{{--{{$countries->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

    </div>

    <div class="modal fade" id="subCat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اضافة نص جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('issues.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">



                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">النص بالعربية</label>
                            <div class="col-lg-12">
                                <input name="ar_issue" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">النص بالانجليزية</label>
                            <div class="col-lg-12">
                                <input name="en_issue" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput"> تحديد</label>
                            <div class="col-lg-12">
                                <select name="is_captin" class="form-control digits" id="exampleFormControlSelect9" required>
                                    <option value="0" >خاص بالمستخدم</option>
                                    <option value="1" >خاص بالسائق</option>
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

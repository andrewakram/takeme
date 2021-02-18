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
                                الحسابات البنكية الخاصة بالتطبيق
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الدول</li>
                            </ol>--}}
                        </div>
                        <div style="float: left">
                            {{--@if(admin()->hasPermissionTo('Add country'))--}}
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#subCat"><i class="icon-plus"></i>
                                اضافة حساب جديد
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

                                <th scope="col"> اسم البنك </th>
                                <th scope="col"> رقم الحساب </th>
                                <th scope="col"> اللوجو </th>

                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>

                                    <td>{{$c->bank_name}}</td>
                                    <td>{{$c->account_no}}</td>
                                    @if($c->image != NULL)
                                        <th><img src="{{$c->image}}"  width="50px" height="50px"></th>
                                    @else
                                        <th> - </th>
                                    @endif

                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <button title="تعديل" type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        {{--@endif--}}

                                        <a href="{{route('deleteBankAccount',$c->id)}}" data-original-title="" title="">
                                            <button title="" class="btn btn-danger" data-original-title="حذف">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                        </a>
                                    </td>

                                    <div class="modal fade" id="edit_{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">تعديل الحساب</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form class="form-horizontal" method="post" action="{{route('editBankAccount')}}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="modal-body">
                                                        <input type="hidden" name="bank_account_id" value="{{$c->id}}">


                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">اسم البنك</label>
                                                            <div class="col-lg-12">
                                                                <input name="bank_name" class="form-control" type="text" value="{{$c->bank_name}}" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row ">
                                                            <label class="col-lg-12 control-label text-lg-right" for="textinput">رقم الحساب</label>
                                                            <div class="col-lg-12">
                                                                <input name="account_no" class="form-control" type="text" value="{{$c->account_no}}" >
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
                    <h5 class="modal-title" id="exampleModalLabel">اضافة سبب جديد</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-horizontal needs-validation was-validated" method="post" action="{{route('bank_account.store')}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="modal-body">



                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">اسم البنك</label>
                            <div class="col-lg-12">
                                <input name="bank_name" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">رقم الحساب</label>
                            <div class="col-lg-12">
                                <input name="account_no" class="form-control digits" type="text" required>
                                <div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>
                            </div>
                        </div>
                        <div class="form-group " >
                            <label class="col-lg-12 control-label text-lg-right" for="textinput">الصورة</label>
                            <input name="image" type="file" class="form-control btn-square" {{--required oninvalid="this.setCustomValidity('هذا الحقل مطلوب ادخاله')"--}}>
                            {{--<div class="invalid-feedback">هذا الحقل مطلوب ادخاله .</div>--}}
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

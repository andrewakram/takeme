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
                                الشكاوي و المقترحات
                            </h3>
                            {{--<ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i data-feather="home"></i></a></li>
                                <li class="breadcrumb-item active">الاقسام</li>
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
                                <th scope="col">النوع</th>
                                <th scope="col">الاسم </th>
                                <th scope="col">البريد الالكتروني</th>
                                <th scope="col"> العنوان</th>
                                <th scope="col"> الرسالة</th>
                                <th scope="col">رقم الطلب</th>
                                <th scope="col">نوع الطلب</th>
                                <th scope="col"> المستخدم</th>
                                <th scope="col"> السائق</th>
                                <th scope="col"> المشكلة</th>
                                <th scope="col"> المفقودات</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cats as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})" class="">
                                    <td>{{$c->id}}</td>
                                    <td>
                                        @if($c->type == 0)
                                            <b>خاص بالمستخدم</b>
                                        @elseif($c->type == 1)
                                            <b>خاص بالمندوب</b>
                                        @elseif($c->type == 2)
                                            <b>خاص بالسائق</b>
                                        @endif
                                    </td>
                                    <td>{{$c->name}}</td>
                                    <td>{{$c->email}}</td>
                                    <th> {{$c->title}} </th>
                                    <th> {{$c->description}} </th>
                                    <th> {{isset($c->order_id) ? $c->order_id : '-'}} </th>
                                    <th>
                                        @if($c->type == 0)
                                            <b>بدون طلب</b>
                                        @elseif($c->type == 1)
                                            <b>خاص برحلة</b>
                                        @elseif($c->type == 2)
                                            <b>طلب متاجر</b>
                                        @elseif($c->type == 3)
                                            <b>طلب عادي</b>
                                        @endif
                                    </th>
                                    <th>
                                        @if($c->type == 0)
                                            {{isset($c->user->id) ? $c->user->id : '-'}} <br>
                                            {{isset($c->user->id) ? $c->user->name : '-'}} <br>
                                            {{isset($c->user->id) ? $c->user->email : '-'}} <br>
                                            {{isset($c->user->id) ? $c->user->phone : '-'}}
                                        @elseif($c->type == 1)
                                            {{isset($c->delegate->id) ? $c->delegate->id : '-'}} <br>
                                            {{isset($c->delegate->id) ? $c->delegate->name : '-'}} <br>
                                            {{isset($c->delegate->id) ? $c->delegate->email : '-'}} <br>
                                            {{isset($c->delegate->id) ? $c->delegate->phone : '-'}}
                                        @elseif($c->type == 2)
                                            {{isset($c->driver->id) ? $c->driver->id : '-'}} <br>
                                            {{isset($c->driver->id) ? $c->driver->name : '-'}} <br>
                                            {{isset($c->driver->id) ? $c->driver->email : '-'}} <br>
                                            {{isset($c->driver->id) ? $c->driver->phone : '-'}}
                                        @endif
                                    </th>
{{--                                    <th>--}}
{{--                                        {{isset($c->driver->id) ? $c->driver->id : '-'}} <br>--}}
{{--                                        {{isset($c->driver->id) ? $c->driver->name : '-'}} <br>--}}
{{--                                        {{isset($c->driver->id) ? $c->driver->email : '-'}} <br>--}}
{{--                                        {{isset($c->driver->id) ? $c->driver->phone : '-'}}--}}
{{--                                    </th>--}}
                                    <th>
                                        {{isset($c->issue->id) ? $c->issue->id : '-'}} <br>
                                        {{isset($c->issue->id) ? $c->issue->ar_issue : '-'}} <br>
                                        {{isset($c->issue->id) ? $c->issue->en_issue : '-'}}
                                    </th>
                                    <th>
                                        {{isset($c->lost->id) ? $c->lost->id : '-'}} <br>
                                        {{isset($c->lost->id) ? $c->lost->ar_lost : '-'}} <br>
                                        {{isset($c->lost->id) ? $c->lost->en_lost : '-'}}
                                    </th>
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


@endsection

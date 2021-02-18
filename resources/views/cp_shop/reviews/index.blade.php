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
                                التقييمات
                                 ({{sizeof($reviews)}})
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
                                <th scope="col"> التقييم </th>
                                <th scope="col"> التعليق </th>
                                <th scope="col"> المستخدم </th>
                                <th scope="col"> المتجر </th>
                                <th scope="col">الاختيارات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reviews as $c)
                                <tr id="main_cat_{{$c->id}}" onclick="myFunction({{$c->id}})">
                                    <td>{{$c->id}}</td>
                                    <td>{{$c->rate}}</td>
                                    <td>{{$c->comment}}</td>
                                    <td>{{$c->user_name}}</td>
                                    <td>{{$c->shop_name}}</td>
                                    <td>
                                        {{--@if(admin()->hasPermissionTo('Edit City'))--}}
                                        <a href="{{asset('/reviews/delet/'.$c->id)}}">
                                            <button title="حذف" type="button" class="btn btn-danger" data-toggle="modal" data-target="#edit_{{$c->id}}">
                                                -
                                            </button>
                                        </a>
                                        {{--@endif--}}
                                    </td>
                                </tr>
                            @endforeach
                            {{--<tbody id="sub_cats_{{$category->id}}"></tbody>--}}
                            </tbody>
                        </table>
                    </div>{{--{{$reviews->links()}}--}}
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection

@extends('cp.index')
@section('content')


    <div class="page-body" dir="rtl">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <div class="col-lg-12">
                                    <h3 class="box-title">
                                        <b> التقارير:</b>
                                    </h3>
                                </div>
                                <div class="form-group col-lg-12">
                                    <form role="form" method="post" enctype="multipart/form-data" action="{{route('makeReport')}}" >
                                        {{csrf_field()}}
                                        <div class="row" >


                                            <div class="form-group col-md-4">
                                                <label>[ الفترة ] من </label>
                                                <input name="dateFrom" type="date" required value="" class="form-control" >
                                            </div>

                                            <div class="form-group col-lg-4">
                                                <label>[ الفترة ] الي </label>
                                                <input name="dateTo" type="date" required value="" class="form-control" >
                                            </div>

                                            <div class="form-group  col-lg-3">
                                                <label>نوع التقرير</label>
                                                <select name="type"class="form-control" >
                                                    <option value="" disabled selected>اختر التقرير</option>
                                                    <option value="usersReport" >تقرير المستخدمين</option>
                                                    <option value="delegatesReport" >تقرير المناديب</option>
                                                    <option value="driversReport" >تقرير السائقين</option>
                                                    <option value="shopsReport" >تقرير المتاجر</option>
                                                    <option value="tripsReport" >تقرير الرحلات</option>
                                                    <option value="ordersShopsReport" >تقرير طلبات المتاجر</option>
                                                    <option value="ordersNormalReport" >تقرير الطلبات العادية</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-lg-1">

                                                <br><br>
                                                <button type="submit" class="btn btn-success col-lg-1 ">بحث</button>
                                            </div>



                                        </div>
                                        <!-- /.box-body -->


                                    </form>
                                </div>


                            </div>









                            @yield("usersReport")
                            @yield("shopsReport")
                            @yield("offersReport")


                        </div>
                        <!-- /.box -->{{--{{ $adds->links() }}--}}
                    </div>
                </div>
            </div>
        </div>


    </div>


@endsection

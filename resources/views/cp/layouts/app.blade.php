@if(request()->segment(1)=='en')
    <?php
    Session::put("lang","en"); ?>
@endif
@if(request()->segment(1)=='ar')
    <?php
    Session::put("lang","ar"); ?>
@endif


    <!DOCTYPE html>
{{--@if(request()->segment(1)=='en')
    <html lang="en" dir="ltr">
@else
    <html lang="ar" dir="rtl">
@endif--}}
<html lang="{{Session::get('lang')}}" dir="{{Session::get('lang') == "ar" ? 'rtl' : 'ltr'}}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    {{----}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="endless admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, endless admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{asset('admin/assets/images/logo.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('admin/assets/images/logo.png')}}" type="image/x-icon">
    <title>E3LN</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/fontawesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/feather-icon.css')}}">
    <!-- Plugins css start-->
    {{--<link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/chartist.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/owlcarousel.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/whether-icon.css')}}">
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('admin/assets/css/light-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/summernote.css')}}">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/responsive.css')}}">

    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/photoswipe.css')}}">
    <!-- Plugins css Ends-->

</head>
@if(request()->segment(1)=='en')
    <body main-theme-layout="{{Session::get('lang') == "ar" ? 'rtl' :'ltr'}}">
    @else
        <body main-theme-layout="{{Session::get('lang') == "ar" ? 'rtl' :'ltr'}}"">

        @endif
        <!-- Loader starts-->
        <div class="loader-wrapper">
            <div class="loader bg-white">
                <div class="whirly-loader"> </div>
            </div>
        </div>
        <!-- Loader ends-->
        <!-- page-wrapper Start-->
        <div class="page-wrapper">
            <!-- Page Header Start-->
            <div class="page-main-header">
                <div class="main-header-right row">
                    <div class="{{Session::get("lang") =="ar" ? 'main-header-right' : 'main-header-left'}} d-lg-none">
                        <div class="logo-wrapper"><a href="dashboard"><img src="{{asset('admin/assets/images/logo-12.png')}}" alt=""></a></div>
                    </div>
                    <div class="{{Session::get("lang") =="ar" ? 'nav-left' : 'nav-right'}} col p-0" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                        <ul class="nav-menus">
                            <li>
                                {{--<form class="form-inline search-form" action="#" method="get">--}}
                                {{--<div class="form-group">--}}
                                {{--<div class="Typeahead Typeahead--twitterUsers">--}}
                                {{--<div class="u-posRelative">--}}
                                {{--<input class="Typeahead-input form-control-plaintext" id="demo-input" type="text" name="q" placeholder="Search...">--}}
                                {{--<div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div><span class="d-sm-none mobile-search"><i data-feather="search"></i></span>--}}
                                {{--</div>--}}
                                {{--<div class="Typeahead-menu"></div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</form>--}}
                            </li>
                            {{--<li class="onhover-dropdown"><i data-feather="bell"></i><span class="dot"></span>--}}
                            {{--<ul class="notification-dropdown onhover-show-div">--}}
                            {{--<li>Notification <span class="badge badge-pill badge-primary pull-right">3</span></li>--}}
                            {{--<li>--}}
                            {{--<div class="media">--}}
                            {{--<div class="media-body">--}}
                            {{--<h6 class="mt-0"><span><i class="shopping-color" data-feather="shopping-bag"></i></span>Your order ready for Ship..!<small class="pull-right">9:00 AM</small></h6>--}}
                            {{--<p class="mb-0">Lorem ipsum dolor sit amet, consectetuer.</p>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<div class="media">--}}
                            {{--<div class="media-body">--}}
                            {{--<h6 class="mt-0 txt-success"><span><i class="download-color font-success" data-feather="download"></i></span>Download Complete<small class="pull-right">2:30 PM</small></h6>--}}
                            {{--<p class="mb-0">Lorem ipsum dolor sit amet, consectetuer.</p>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                            {{--<div class="media">--}}
                            {{--<div class="media-body">--}}
                            {{--<h6 class="mt-0 txt-danger"><span><i class="alert-color font-danger" data-feather="alert-circle"></i></span>250 MB trash files<small class="pull-right">5:00 PM</small></h6>--}}
                            {{--<p class="mb-0">Lorem ipsum dolor sit amet, consectetuer.</p>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                            {{--<li class="bg-light txt-dark"><a href="index.html#">All</a> notification</li>--}}
                            {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a href="index.html#"><i class="right_side_toggle" data-feather="message-circle"></i><span class="dot"></span></a></li>--}}

                            <li class="onhover-dropdown">
                                <div class="media align-items-center"><i class="fa fa-4x fa-language" aria-hidden="true" class="align-self-center {{Session::get("lang") =="ar" ? 'pull-left' : 'pull-right'}} img-50 rounded-circle"></i>
                                    <div class="dotted-animation"><span class="animate-circle"></span><span class="main-circle"></span></div>
                                </div>
                                <ul class="profile-dropdown onhover-show-div p-20 {{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <li>
                                        <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            {{ $properties['native'] }}
                                        </a>
                                    </li>
                                    @endforeach

                                </ul>
                            </li>

                            <li class="onhover-dropdown">
                                <div class="media align-items-center"><img class="align-self-center {{Session::get("lang") =="ar" ? 'pull-left' : 'pull-right'}} img-50 rounded-circle" src="{{admin()->image}}" alt="header-user">
                                    <div class="dotted-animation"><span class="animate-circle"></span><span class="main-circle"></span></div>
                                </div>
                                <ul class="profile-dropdown onhover-show-div p-20 {{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                                    <li><a href="{{asset( Session::get("lang") . '/admin/edit')}}"><i data-feather="user"></i>                                    Edit Profile</a></li>
                                    {{--<li><a href="index.html#"><i data-feather="mail"></i>                                    Inbox</a></li>--}}
                                    {{--<li><a href="index.html#"><i data-feather="lock"></i>                                    Lock Screen</a></li>--}}
                                    {{--<li><a href="index.html#"><i data-feather="settings"></i>                                    Settings</a></li>--}}
                                    <li><a href="{{asset( Session::get("lang") . '/admin/logout')}}"><i data-feather="log-out"></i>                                    Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="d-lg-none mobile-toggle {{Session::get("lang") =="ar" ? 'pull-left' : 'pull-right'}}"><i data-feather="more-horizontal"></i></div>
                    </div>
                    <script id="result-template" type="text/x-handlebars-template">
                        <div class="ProfileCard u-cf">
                            <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
                            <div class="ProfileCard-details">
                                <div class="ProfileCard-realName"></div>
                            </div>
                        </div>
                    </script>
                    <script id="empty-template" type="text/x-handlebars-template">
                        <div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div>

                    </script>
                </div>
            </div>
            <!-- Page Header Ends                              -->
            <!-- Page Body Start-->
            @if(request()->segment(1)=='en')
                <div class="page-body-wrapper" dir="ltr">
                    @else
                        <div class="page-body-wrapper" dir="rtl">
                        @endif
                        <!-- Page Sidebar Start-->

                            <div class="page-sidebar" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                                <div class="{{Session::get("lang") =="ar" ? 'main-header-right' : 'main-header-left'}} d-none d-lg-block">
                                    {{--<div class="logo-wrapper"><a href="/admin/dashboard"><img src="{{asset('admin/assets/images/logo-12.png')}}" alt=""></a></div>--}}
                                    <a href="{{asset( Session::get("lang") . '/admin/dashboard')}}">
                                        <li class="xnn-logo">
                                            Jaz
                                        </li>
                                    </a>
                                </div>
                                <div class="sidebar custom-scrollbar" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                                    <div class="sidebar-user text-center">
                                        <div><img class="img-60 rounded-circle" src="{{admin()->image}}" alt="#">
                                            {{--<div class="profile-edit"><a href="edit-profile.html" target="_blank"><i data-feather="edit"></i></a></div>--}}
                                        </div>
                                        <h6 class="mt-3 f-14">{{admin()->name}}</h6>
                                        <p>general manager.</p>
                                    </div>
                                    <ul class="sidebar-menu" dir="{{Session::get("lang") =="ar" ? 'rtl' : 'ltr'}}">
                                        @if(admin()->hasPermissionTo('View user'))
                                            <li><a class="sidebar-header" href="#"><i data-feather="users"></i><span>{{trans('admin.users')}}</span><i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i></a>
                                                <ul class="sidebar-submenu">
                                                    <li><a href="{{asset( Session::get("lang") . '/admin/users/type/active')}}"><i class="fa fa-circle"></i>{{trans('admin.active')}}</a></li>
                                                    <li><a href="{{asset( Session::get("lang") . '/admin/users/type/suspended')}}"><i class="fa fa-circle"></i>{{trans('admin.suspended')}}</a></li>
                                                </ul>
                                            </li>
                                        @endif

                                        @if(admin()->hasPermissionTo('View company worker') || admin()->hasPermissionTo('View app worker') )
                                            <li><a class="sidebar-header" href="#"><i data-feather="truck"></i><span>{{trans('admin.workers')}}</span><i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i></a>
                                                <ul class="sidebar-submenu">
                                                    @if(admin()->hasPermissionTo('View company worker'))
                                                        <li><a href="{{asset( Session::get("lang") . '/admin/workers_company')}}"><i class="fa fa-circle"></i>{{trans('admin.companyWorkers')}}</a></li>
                                                    @endif
                                                    @if(admin()->hasPermissionTo('View app worker'))
                                                        <li><a class="sidebar-header" href="#"><span>{{trans('admin.appWorkers')}}</span><i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i></a>
                                                            <ul class="sidebar-submenu">
                                                                <li><a href="{{asset( Session::get("lang") . '/admin/workers/active')}}"><i class="fa fa-circle"></i>{{trans('admin.active')}}</a></li>
                                                                <li><a href="{{asset( Session::get("lang") . '/admin/workers/suspended')}}"><i class="fa fa-circle"></i>{{trans('admin.suspended')}}</a></li>
                                                            </ul>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        @endif

                                        @if(admin()->hasPermissionTo('View admins'))
                                            <li>
                                                <a class="sidebar-header" href="{{route('admins.index')}}">
                                                    <i data-feather="command"></i>
                                                    <span>{{trans('admin.admins')}} </span>
                                                    <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                </a>

                                            </li>
                                        @endif

                                        @if(admin()->hasPermissionTo('View categories'))
                                            <li>
                                                <a class="sidebar-header" href="{{route('categories.index')}}">
                                                    <i data-feather="package"></i>
                                                    <span>{{trans('admin.categories')}} </span>
                                                    <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                </a>

                                            </li>
                                        @endif

                                        @if(admin()->hasPermissionTo('View orders'))
                                            <li>
                                                <a class="sidebar-header" href="{{route('orders')}}">
                                                    <i data-feather="shopping-cart"></i>
                                                    <span>{{trans('admin.orders')}} </span>
                                                    <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                </a>

                                            </li>
                                        @endif

                                        @if(admin()->hasPermissionTo('View orders'))
                                            <li>
                                                <a class="sidebar-header" href="{{route('costsReport')}}">
                                                    <i data-feather="file"></i>
                                                    {{--<i class="fa fa-file-excel-o"></i>--}}
                                                    <span>{{trans('admin.CsotsReport')}} </span>
                                                    <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                </a>

                                            </li>
                                        @endif


                                            @if(admin()->hasPermissionTo('View cities'))
                                                <li>
                                                    <a class="sidebar-header" href="{{route('cities.index')}}">
                                                        <i data-feather="shopping-cart"></i>
                                                        <span>{{trans('admin.cities')}} </span>
                                                        <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                    </a>
                                                </li>
                                            @endif


                                        @if(admin()->hasPermissionTo('View settings'))
                                            <li><a class="sidebar-header" href="#"><i data-feather="users"></i><span>{{trans('admin.settings')}}</span><i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i></a>
                                                <ul class="sidebar-submenu">
                                                    <li><a href="{{asset( Session::get("lang") . '/admin/settings/about_us')}}"><i class="fa fa-circle"></i>{{trans('admin.aboutUs')}}</a></li>
                                                    <li><a href="{{asset( Session::get("lang") . '/admin/settings/term_condition')}}"><i class="fa fa-circle"></i>{{trans('admin.termsConditions')}}</a></li>
                                                    <li><a href="{{route('complainSuggest')}}"><i class="fa fa-circle"></i>{{trans('admin.complainsSuggestion')}}</a></li>
                                                </ul>
                                            </li>
                                        @endif

                                            {{--@if(admin()->hasPermissionTo('View admins'))--}}
                                                <li>
                                                    <a class="sidebar-header" href="{{route('websiteData')}}">
                                                        <i data-feather="command"></i>
                                                        <span>Website </span>
                                                        <i class="fa {{Session::get("lang") =="ar" ? 'fa-angle-left pull-left' : 'fa-angle-right pull-right'}}"></i>
                                                    </a>

                                                </li>
                                            {{--@endif--}}

                                    </ul>
                                </div>
                            </div>
                            <!-- Page Sidebar Ends-->

                            @yield('content')


                        </div>
                </div>
                <!-- END MESSAGE BOX-->
                <!-- latest jquery-->
                <script src="{{asset('admin/assets/js/jquery-3.2.1.min.js')}}"></script>
                <!-- Bootstrap js-->
                <script src="{{asset('admin/assets/js/bootstrap/popper.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/bootstrap/bootstrap.js')}}"></script>
                <!-- feather icon js-->
                <script src="{{asset('admin/assets/js/icons/feather-icon/feather.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/icons/feather-icon/feather-icon.js')}}"></script>
                <!-- Sidebar jquery-->
                <script src="{{asset('admin/assets/js/sidebar-menu.js')}}"></script>
                <script src="{{asset('admin/assets/js/config.js')}}"></script>
                <!-- Plugins JS start-->
                <script src="{{asset('admin/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/buttons.colVis.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.autoFill.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.select.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/buttons.bootstrap4.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.bootstrap4.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.responsive.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/responsive.bootstrap4.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.keyTable.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.colReorder.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.fixedHeader.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.rowReorder.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/dataTables.scroller.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/datatable/datatable-extension/custom.js')}}"></script>


                {{--<script src="{{asset('admin/assets/js/chart/chartist/chartist.js')}}"></script>--}}
                {{--<script src="{{asset('admin/assets/js/chart/knob/knob.min.js')}}"></script>--}}
                {{--<script src="{{asset('admin/assets/js/chart/knob/knob-chart.js')}}"></script>--}}
                <script src="{{asset('admin/assets/js/prism/prism.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/clipboard/clipboard.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/counter/jquery.waypoints.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/counter/jquery.counterup.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/counter/counter-custom.js')}}"></script>
                <script src="{{asset('admin/assets/js/custom-card/custom-card.js')}}"></script>
                <script src="{{asset('admin/assets/js/owlcarousel/owl.carousel.js')}}"></script>
                <script src="{{asset('admin/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
                <script src="{{asset('admin/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
                <script src="{{asset('admin/assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
                <script src="{{asset('admin/assets/js/notify/bootstrap-notify.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/dashboard/default.js')}}"></script>
                <script src="{{asset('admin/assets/js/notify/index.js')}}"></script>
                {{--<script src="{{asset('admin/assets/js/typeahead/handlebars.js')}}"></script>--}}
                {{--<script src="{{asset('admin/assets/js/typeahead/typeahead.bundle.js')}}"></script>--}}
                {{--<script src="{{asset('admin/assets/js/typeahead/typeahead.custom.js')}}"></script>--}}
                <script src="{{asset('admin/assets/js/chat-menu.js')}}"></script>
                <script src="{{asset('admin/assets/js/general-widget.js')}}"></script>
                <script src="{{asset('admin/assets/js/height-equal.js')}}"></script>
                <script src="{{asset('admin/assets/js/tooltip-init.js')}}"></script>
                <script src="{{asset('admin/assets/js/editor/summernote/summernote.js')}}"></script>
                <script src="{{asset('admin/assets/js/editor/summernote/summernote.custom.js')}}"></script>
                {{--<script src="{{asset('admin/assets/js/typeahead-search/handlebars.js')}}"></script>--}}
                {{--<script src="{{asset('admin/assets/js/typeahead-search/typeahead-custom.js')}}"></script>--}}
            <!-- Plugins JS Ends-->
                <!-- Theme js-->
                <script src="{{asset('admin/assets/js/script.js')}}"></script>
                {{--<script src="{{asset('admin/assets/js/theme-customizer/customizer.js')}}"></script>--}}
                <script src="{{asset('admin/assets/js/isotope.pkgd.js')}}"></script>
                <script src="{{asset('admin/assets/js/photoswipe/photoswipe.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/photoswipe/photoswipe.js')}}"></script>
                <script src="{{asset('admin/assets/js/animation/wow/wow.min.js')}}"></script>
                <script src="{{asset('admin/assets/js/animation/wow/wow-init.js')}}"></script>
                <!-- Plugin used-->



                <!--Load the AJAX API-->
                <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                <script type="text/javascript">google.load('visualization', '1.0', {'packages':['corechart']});</script>

                <!-- // Load the Visualization API library and the piechart library.
                google.load('visualization', '1.0', {'packages':['corechart']});
                google.setOnLoadCallback(drawChart);
                   // ... draw the chart... -->
                <!-- </script> -->

                <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
                <script src="{{ asset('chart/google/google-chart-loader.js')}}"></script>
                <script src="{{ asset('chart/google/google-chart.js')}}"></script>

                <script>
                    $(document).ready(function () {
                        /*users charts*/
                        @if(isset($users_charts))
                        if ($("#users").length > 0) {
                            var data = google.visualization.arrayToDataTable([
                                ["Element", "users", {role: "style"}],
                                    @if(isset($users_charts))
                                    @foreach($users_charts as $test)
                                [" {{$test->date  }} ", {{ $test->count }} , "#1D2D44"],
                                @endforeach
                                @endif
                            ]);
                            var view = new google.visualization.DataView(data);
                            view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);
                            var options = {
                                width: '100%',
                                height: 400,
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("users"));
                            chart.draw(view, options);
                        }
                        @endif

                        /*workers charts*/
                        @if(isset($workers_charts))
                        if ($("#workers").length > 0) {
                            var data = google.visualization.arrayToDataTable([
                                ["Element", "workers", {role: "style"}],
                                    @if(isset($workers_charts))
                                    @foreach($workers_charts as $test)
                                [" {{$test->date  }} ", {{ $test->count }} , "#1D2D44"],
                                @endforeach
                                @endif
                            ]);
                            var view = new google.visualization.DataView(data);
                            view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);
                            var options = {
                                width: '100%',
                                height: 400,
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("workers"));
                            chart.draw(view, options);
                        }
                        @endif

                        /*orders charts*/
                        @if(isset($orders_charts))
                        if ($("#orders").length > 0) {
                            var data = google.visualization.arrayToDataTable([
                                ["Element", "workers", {role: "style"}],
                                    @if(isset($orders_charts))
                                    @foreach($orders_charts as $test)
                                [" {{$test->date  }} ", {{ $test->count }} , "#1D2D44"],
                                @endforeach
                                @endif
                            ]);
                            var view = new google.visualization.DataView(data);
                            view.setColumns([0, 1,
                                {
                                    calc: "stringify",
                                    sourceColumn: 1,
                                    type: "string",
                                    role: "annotation"
                                },
                                2]);
                            var options = {
                                width: '100%',
                                height: 400,
                                bar: {groupWidth: "95%"},
                                legend: {position: "none"},
                            };
                            var chart = new google.visualization.ColumnChart(document.getElementById("orders"));
                            chart.draw(view, options);
                        }
                        @endif


                    });
                </script>
                <script>
                    $(document).ready( function () {
                        @if(request()->segment(1)=='en')
                        <?php Session::put("lang","en"); ?>
                        @endif
                        @if(request()->segment(1)=='ar')
                        <?php Session::put("lang","ar"); ?>
                        @endif
                    });

                </script>

        </body>
</html>

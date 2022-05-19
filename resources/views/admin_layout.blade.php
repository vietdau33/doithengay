<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Admin Panel</title>

    <!-- Icons -->
    <link rel="shortcut icon" href="{{ asset('image/logo-trans.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('image/logo-trans.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo-trans.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" href="{{ asset('vendor/alertify/css/alertify.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/alertify/css/themes/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('_admin_/css/codebase.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.css') }}">

    @yield('style')
</head>
<body class="custom-scrollbar">

<div id="page-container" class="sidebar-o sidebar-inverse enable-page-overlay side-scroll page-header-fixed main-content-narrow">
    <nav id="sidebar">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow px-15">
                <!-- Normal Mode -->
                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700 d-flex" href="{{ route('admin.home') }}">
                            <span class="font-size-xl text-dual-primary-dark">Auto</span>
                            <span class="font-size-xl text-primary">card</span>
                            <span class="font-size-xl text-success">VN</span>
                        </a>
                    </div>
                    <!-- END Logo -->
                </div>
                <!-- END Normal Mode -->
            </div>
            <!-- END Side Header -->

            <hr class="m-0" style="box-shadow: 0 0 4px 1px #2d3238; border-top-color: #2d3238;" />

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li>
                        <a href="{{ route('admin.home') }}" data-id="dashboard">
                            <i class="si si-cup"></i>
                            <span class="sidebar-mini-hide">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-user"></i>
                            <span class="sidebar-mini-hide">Thành viên</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.user.active') }}" data-id="user-active">Hoạt động</a></li>
                            <li><a href="{{ route('admin.user.block') }}" data-id="user-block">Bị khóa</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-refresh"></i>
                            <span class="sidebar-mini-hide">Rút tiền</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.withdraw-request') }}" data-id="withdraw-request">Yêu cầu rút tiền</a></li>
                            <li><a href="{{ route('admin.withdraw-history') }}" data-id="withdraw-history">Lịch sử yêu cầu</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-credit-card"></i>
                            <span class="sidebar-mini-hide">Quản trị bán thẻ cào</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.buycard-request') }}" data-id="buycard-request">Card chờ duyệt</a></li>
                            <li><a href="{{ route('admin.buycard-request.success') }}" data-id="buycard-request.success">Card bán thành công</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-credit-card"></i>
                            <span class="sidebar-mini-hide">Quản trị thu thẻ cào</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.tradecard-request') }}" data-id="tradecard-request">Danh sách yêu cầu</a></li>
                            <li><a href="{{ route('admin.tradecard-request.success') }}" data-id="tradecard-request.success">Danh sách thu thành công</a></li>
                            <li><a href="{{ route('admin.tradecard-request.fail') }}" data-id="tradecard-request.fail">Danh sách thu thất bại</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-credit-card"></i>
                            <span class="sidebar-mini-hide">Thanh toán cước</span>
                        </a>
                        <ul class="nav-main__childrent">
                            @foreach(config('bill') as $type => $bill)
                                <li><a href="{{ route('admin.bill', ['type' => $type]) }}" data-id="pay-bill-{{ $type }}">{{ $bill['text'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-calculator"></i>
                            <span class="sidebar-mini-hide">Quản trị chiết khấu</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.feature.discount') }}" data-id="discount">Chiết khấu đổi thẻ</a></li>
                            <li><a href="{{ route('admin.feature.discount_buy') }}" data-id="discount_buy">Chiết khấu bán thẻ</a></li>
                            <li><a href="{{ route('admin.feature.discount_bill') }}" data-id="discount_bill">Chiết khấu gạch cước</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-submenu" data-toggle="nav-submenu" href="#">
                            <i class="si si-bulb"></i>
                            <span class="sidebar-mini-hide">Quản trị tính năng</span>
                        </a>
                        <ul class="nav-main__childrent">
                            <li><a href="{{ route('admin.feature.trade') }}" data-id="trade">Thu thẻ cào</a></li>
                            <li><a href="{{ route('admin.feature.buy') }}" data-id="buy">Bán thẻ cào</a></li>
                            <li><a href="{{ route('admin.feature.bill') }}" data-id="bill">Gạch cước</a></li>
                            <li><a href="{{ route('admin.notification.home') }}" data-id="notification">Thông báo ở trang chủ</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('admin.system-setting') }}" data-id="system-setting">
                            <i class="si si-power"></i>
                            <span class="sidebar-mini-hide">Quản lý hệ thống</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.system-bank') }}" data-id="system-setting">
                            <i class="si si-settings"></i>
                            <span class="sidebar-mini-hide">Quản lý thẻ ngân hàng</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logs') }}" data-id="trace-log">
                            <i class="si si-calculator"></i>
                            <span class="sidebar-mini-hide">Quản lý logs</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- Sidebar Content -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="content-header-section">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                    <i class="fa fa-navicon"></i>
                </button>
                <!-- END Toggle Sidebar -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="content-header-section">
                <!-- User Dropdown -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">{{ user()->fullname }}</span>
                        <i class="fa fa-angle-down ml-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                        <a class="dropdown-item" href="{{ route('admin.change_password') }}">
                            <i class="si si-user mr-5"></i> Đổi mật khẩu
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('auth.logout') }}">
                            <i class="si si-logout mr-5"></i> Sign Out
                        </a>
                    </div>
                </div>
                <!-- END User Dropdown -->
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        @yield('contents')
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="opacity-0">
        <div class="font-size-sm">
            <p class="m-0 p-3 text-center">Bản quyền &copy; {{ request()->getHost() }} {{ date('Y') }}</p>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<script src="{{ asset('_admin_/js/codebase.core.min.js') }}"></script>
<script src="{{ asset('_admin_/js/codebase.app.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui/jquery-ui.js') }}"></script>

<!-- Page JS Plugins -->
<script src="{{ asset('_admin_/js/plugins/chartjs/Chart.bundle.min.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('js/request.js') }}"></script>
<script src="{{ asset('vendor/alertify/alertify.js') }}"></script>
<script src="{{ asset('js/autosize.js') }}"></script>
<script src="{{ asset('_admin_/js/pages/home.min.js') }}"></script>
<script src="{{ asset('_admin_/js/app.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script id="script_save_error">
    //assign errors
    window.errors = {!! json_encode($errors->toArray()) !!};

    //show notif
    @if(session()->has('notif'))
        alertify.alert('Notification', '{{session()->pull('notif')}}');
    @endif

    //show error
    @if(session()->has('mgs_error'))
        alertify.alert('Error', '{{session()->pull('mgs_error')}}');
        $('.alertify .ajs-header').addClass('alert-danger');
    @endif
    //active menu
    @if(session()->has('menu-active'))
        let activeElement = $('[data-id="{{ session()->pull('menu-active') }}"]');
        if(activeElement.length > 0) {
            activeElement.addClass('active');
            let ulParent = activeElement.closest('ul');
            if(ulParent.hasClass('nav-main__childrent')) {
                ulParent.parent('li').addClass('open');
            }
        }
    @endif

    //remove elm script
    document.querySelector('#script_save_error').remove();
</script>

@yield('script')

</body>
</html>

@include('site.trade.layout.header')

<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div id="dismiss">
            <i class="fas fa-arrow-left"></i>
        </div>

        <div class="sidebar-header">
            <h3 class="m-0" style="font-family: bold;">Dashboard</h3>
        </div>

        <ul class="list-unstyled components">
            <div class="d-flex justify-content-start align-items-start px-2 mb-3">
                <div>
                    @php
                        $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                    @endphp
                    <img src="{{$url}}" width="45px" height="45px"
                         class="rounded" style="object-fit: contain; object-position: top;">
                </div>
                <div class="pl-2">
                    <span class="d-block">{{auth()->user()->name}}</span>
                    <small class="text-secondary d-block">{{auth()->user()->email}}</small>
                </div>
            </div>

            @if(auth()->user()->is_admin)
                <li class="{{is_active_menu('admin')}}">
                    <a href="{{url('/admin')}}"><i class="fal fa-tachometer-alt mr-2"></i>Admin Dashboard</a>
                </li>
            @endif
            <li class="{{is_active_menu('market')}}">
                <a href="{{url('market')}}"><i class="fal fa-analytics mr-2"></i>Market</a>
            </li>
            <li class="{{is_active_menu('deposit')}}">
                <a href="{{url('deposit')}}"><i class="fal fa-wallet mr-2"></i>Make Deposit</a>
            </li>
            <li class="{{is_active_menu('withdrawal')}}">
                <a href="{{url('withdrawal')}}"><i class="fab fa-get-pocket mr-2"></i>Get Withdrawal</a>
            </li>
            <li class="{{is_active_menu('transactions')}}">
                <a href="{{url('transactions')}}"><i class="fal fa-sort-alt mr-2"></i>Transactions</a>
            </li>
            <li class="{{is_active_menu('trade-history')}}">
                <a href="{{url('trade-history')}}"><i class="fal fa-history mr-2"></i>Trading History</a>
            </li>
            <li class="{{is_active_menu('referral')}}">
                <a href="{{url('referral')}}"><i class="fal fa-link mr-2"></i>Referrals</a>
            </li>
            <li class="{{is_active_menu('withdrawal-account')}}">
                <a href="{{url('withdrawal-account')}}"><i class="fal fa-dollar-sign mr-2"></i>Withdrawal Account</a>
            </li>
            <li class="{{is_active_menu('account')}}">
                <a href="{{url('account')}}"><i class="fal fa-user mr-2"></i>My Account</a>
            </li>
        </ul>

        <ul class="list-unstyled CTAs">
            <li>
                <a href="{{url('logout')}}" class="download">
                    <i class="fa fa-sign-out mr-2"></i>Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Page Content  -->
    <div id="content">

        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid p-0 row">
                <div class="col-md-6">
                    <div class="d-flex justify-content-start align-items-center">
                        <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary ml-1" style="border: none !important;">
                            <i class="fas fa-align-left"></i>
                        </button>
                        <h3 class="text-white m-0 ml-2" style="font-family: med;">BTC<span class="text-success">Ride</span></h3>
                        <span class="text-secondary ml-2"><i>Web Trading Platform</i></span>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success px-4 py-2"><span id="balance">Balance:<b>${{sprintf("%0.2f", (auth()->user()->account_balance))}}</b></span></button>
                </div>
{{--                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--                    <i class="fas fa-align-justify"></i>--}}
{{--                </button>--}}

{{--                <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
{{--                    <ul class="nav navbar-nav ml-auto">--}}
{{--                        <li class="nav-item active">--}}
{{--                            <a class="nav-link" href="#">Page</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="#">Page</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="#">Page</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="#">Page</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
            </div>
        </nav>

        <div class="px-3 py-2 text-white">
            <div>
                <h3 class="m-0 py-3 d-inline-block border-bottom border-secondary text-success" style="font-family: bold;">@yield('title')</h3>
            </div>
            @yield('content')
        </div>
    </div>
</div>

<div class="overlay"></div>

@include('site.trade.layout.footer')

<script>
    function setAccountBalance(){
        $.ajax({
            url: "{{url('get-account-balance')}}",
            type: "GET",
            cache: false,
            processData: false,
            contentType: "application/json; charset=UTF-8",
            success: function (res) {
                if (res.success == true) {
                        $('#balance').html('Balance:$<b>' + parseFloat(res.data.account_balance).toFixed(2) + '</b>');
                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(textStatus+' : '+errorThrown);
            }
        });
    }
</script>

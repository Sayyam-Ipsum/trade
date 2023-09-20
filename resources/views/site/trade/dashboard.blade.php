@extends('site.trade.layout.index')

@section('page-title')
    Dashboard
@stop

@section('title')
    Dashboard
@stop

@section('content')
        <div class="col-md-4 p-0">
            <div class="card bg-transparent border-transparent rounded shadow">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-start align-items-center">
                        <div>
                            @php
                                $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                            @endphp
                            <img src="{{$url}}" width="70px" height="70px"
                                 class="rounded-circle p-1 border border-success" style="object-fit: contain;">
                        </div>
                        <div class="ml-3">
                            <p class="m-0 text-white" style="font-family: regular;">{{auth()->user()->name}} (BTR{{auth()->user()->uuid}})</p>
                            <small class="text-success d-block" style="font-family: light;">{{auth()->user()->phone_number}}</small>
                            <small class="text-success d-block" style="font-family: light;">{{auth()->user()->email}}</small>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary mb-2">
                            <a href="{{url('transactions')}}" class="py-1">Total Deposits</a>
                            <span>{{$data['count_deposits']}}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary mb-2">
                            <a href="{{url('transactions')}}" class="py-1">Total Withdraws</a>
                            <span>{{$data['count_withdrawals']}}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary mb-2">
                            <a href="{{url('trade-history')}}" class="py-1">Total Trades</a>
                            <span>{{$data['count_trades']}}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{url('referral')}}" class="py-1">Referrals</a>
                            <span>{{$data['count_referrals']}}</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a class="btn btn-outline-success w-50" href="{{url('deposit')}}"><i class="fal fa-wallet mr-1"></i>Deposit</a>
                        <a class="btn btn-success w-50 ml-1" href="{{url('withdrawal')}}"><i class="fab fa-get-pocket mr-1"></i>Withdraw</a>
                    </div>
                </div>
            </div>
        </div>
@stop

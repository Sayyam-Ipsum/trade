@extends('site.trade.layout.index')

@section('page-title')
    Referrals
@stop

@section('title')
    Referrals
@stop

@section('content')
    <div class="row mt-3">
        <div class="col-md-4 mt-2">
            <div class="p-0 shadow bg-dark border-0" style="border-radius: 10px; !important;">
                <div class="p-2 d-flex justify-content-between align-items-center">
                    <div class="">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                        <span class="d-flex justify-content-center align-items-center bg-info"
                              style="width: 40px; height: 40px; border-radius: 10px;">
                            <i class="fas fa-link text-white"></i>
                        </span>
                            </div>
                            <div>
                                <span class="ml-2 text-white" style="font-size: 16px; font-weight: 550;">Total Referrals</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="p-1 text-white">{{@$referrals}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-2">
            <div class="p-0 shadow bg-dark border-0" style="border-radius: 10px; !important;">
                <div class="p-2 d-flex justify-content-between align-items-center">
                    <div class="">
                        <div class="d-flex justify-content-start align-items-center">
                            <div>
                        <span class="d-flex justify-content-center align-items-center bg-warning"
                              style="width: 40px; height: 40px; border-radius: 10px;">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </span>
                            </div>
                            <div>
                                <span class="ml-2 text-white" style="font-size: 16px; font-weight: 550;">Total Earning</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="p-1 text-white">${{@$referrals * @$referrer_amount}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

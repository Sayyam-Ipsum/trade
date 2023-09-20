@extends('site.trade.layout.index')

@section('page-title')
    Withdrawals
@stop

@section('title')
    Get Withdrawal
@stop

@section('content')
    <div class="card border-0">
        <div class="card-body bg-self p-2">
            <form method="post" autocomplete="off" name="withdrawal-form" id="withdrawal-form" action="{{url('withdrawal')}}">
                @csrf
                <input type="hidden" name="user_id" value="{{auth()->id()}}">
                @if(count($accounts) < 1)
                    <small class="text-danger">
                        No withdrawal account is configure in settings. Please go to Account menu to set the withdrawal account.
                    </small>
                @endif

                <div class="row mt-2">
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="account">Account</label>
                        <select class="form-control bg-self shadow-none" name="account" id="account" required>
                            <option value="">Select Account</option>
                            @if(count($accounts) > 0)
                                @foreach($accounts as $account)
                                    <option value="{{$account->id}}">{{$account->bank}}</option>
                                @endforeach
                            @endif
                        </select>
                        <label id="account-error" class="error" for="account"></label>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="amount">
                            Amount
                            @if($withdrawalExtraChargesPercentage > 0)
                                <small class="text-success" style="font-size: 12px;">
                                    ({{$withdrawalExtraChargesPercentage}}% withdrawal charges will be applied )
                                </small>
                            @endif
                        </label>

                        <input type="number" maxlength="11" min="1" class="form-control shadow-none" name="amount"
                               id="amount" placeholder="0" required oninput="getAmountAfterDeduction()">
                    </div>
                    @if($withdrawalExtraChargesPercentage > 0)
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="amount_after_deduction">Amount After Deduction</label>
                        <input type="text" maxlength="11" readonly class="form-control shadow-none"
                               name="amount_after_deduction" id="amount_after_deduction" required>
                    </div>
                    @endif
                </div>

                <div class="text-center">
                    <button class="btn px-4 btn-success" type="submit">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
@stop


@section('scripts')
    <script>
        function getAmountAfterDeduction() {
            if($('#amount').val() > {{auth()->user()->account_balance}}){
                toast('Withdrawal amount should be less than account balance', 'warning');
                return true;
            }
            @if($withdrawalExtraChargesPercentage > 0) {
                let deducted_amount = ($('#amount').val() / 100) * {{$withdrawalExtraChargesPercentage}};
                $('#amount_after_deduction').val($('#amount').val() - deducted_amount);
            }
            @else {
                return true;
            }
            @endif
        }

        $(document).ready(function (){
            $("#withdrawal-form").validate({
                rules:{
                    amount: {
                        required:true,
                        min: 1
                    },
                    account: {
                        required:true
                    }
                },
                messages:{
                    amount: {
                        required:"Please enter amount*",
                        min: "Value must be greater then 0"
                    },
                    account: {
                        required: "Please select Account*"
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });
        });
    </script>
@stop

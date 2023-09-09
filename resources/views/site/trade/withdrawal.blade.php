@extends('site.trade.layout.index')

@section('title')
    Get Withdrawal
@stop

@section('content')
    <div class="card border-0">
        <div class="card-body bg-self shadow-sm p-0 px-2 py-3">
            <form method="post" name="withdrawal-form" id="withdrawal-form" action="{{url('withdrawal')}}">
                @csrf
                <input type="hidden" name="user_id" value="{{auth()->id()}}">
                @if(count($accounts) < 1)
                    <small class="text-danger">No withdrawal account is configure in settings. Please go to Account menu to set the withdrawal account.</small>
                @endif
                <div class="row">
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
                        <label class="form-label required" for="amount">Amount</label>
                        <input type="number" maxlength="11" class="form-control shadow-none" name="amount" id="amount" required>
                    </div>
                </div>

                <div class="text-center">
                    <button class="btn px-4 btn-success" style="font-family: med;" type="submit">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
@stop


@section('scripts')
    <script>
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

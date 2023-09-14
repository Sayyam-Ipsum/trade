@extends('site.trade.layout.index')

@section('page-title')
    Deposits
@stop

@section('title')
    Make Deposit
@stop

@section('content')
    <div class="card border-0 bg-danger">
        {{--    <div class="card-header">--}}
        {{--        <h6 class="m-0 font-weight-bold">Create Deposit</h6>--}}
        {{--    </div>--}}
        <div class="card-body bg-self shadow-sm p-0 px-2 py-3">
            <form method="post" name="deposit-form" id="deposit-form" action="{{url('deposit')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{auth()->id()}}">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="amount">Amount</label>
                        <input type="number" maxlength="11" class="form-control shadow-none" name="amount" id="amount" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="payment_method">Payment Method</label>
                        <select class="form-control bg-self shadow-none" name="payment_method" id="payment_method" onchange="showPaymentMethodDetail(this)"
                                required="required">
                            <option value="">--Select--</option>
                            @if(count($payment_methods) > 0)
                                @foreach($payment_methods as $method)
                                    <option value="{{$method->id}}">{{$method->bank}}</option>
                                @endforeach
                            @endif
                        </select>
                        <label id="payment_method-error" class="error" for="payment_method"></label>
                    </div>
                </div>

                <div id="detail-box">
                    <small class="text-success">Here is Account Details where you can send money. Once Admin approve deposit, you can do trading. Thank you</small>
                    <div class="row mt-2">
                        <div class="col-md-4 form-group">
                            <label class="form-label required" for="bank">Bank</label>
                            <input type="text" readonly class="form-control shadow-none" name="bank" id="bank">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label required" for="account_name">Account Title</label>
                            <input type="text" readonly class="form-control shadow-none" name="account_name" id="account_name">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label required" for="account_number">Account Number</label>
                            <input type="text" readonly class="form-control shadow-none" name="account_number" id="account_number">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group" id="file-box">
                        <label class="form-label required" for="photo">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" required onchange="setPhoto(this)">
                    </div>
                    <div class="col-md-4" id="photo-box">
                        <img src="" width="100%" height="350px" id="profile-photo"
                             style="object-fit: contain;" class="border border-dark p-1 mb-1">
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-success px-4" style="font-family: med;" type="submit" >Deposit</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function (){
            $("#detail-box").hide();
            $("#file-box").hide();
            $("#photo-box").hide();
            $("#bank").val('');
            $("#account_name").val('');
            $("#account_number").val('');

            $("#deposit-form").validate({
                rules:{
                    amount: {
                        required:true,
                        min: 1
                    },
                    payment_method: {
                        required:true
                    },
                    photo: {
                        required: true
                    }
                },
                messages:{
                    amount: {
                        required:"Please enter amount*",
                        min: "Value must be greater then 0"
                    },
                    payment_method: {
                        required: "Please select Payment Method*"
                    },
                    photo: {
                        required: "Please upload payment receipt screenshot*"
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });
        });

        async function showPaymentMethodDetail(cwt) {
            let method = $("#"+cwt.id).val();
            let details = await getDetail(method);
            $("#bank").val(details.bank);
            $("#account_name").val(details.account_title);
            $("#account_number").val(details.account_no);
            $("#detail-box").slideDown();
            $("#file-box").show();
        }

        function getDetail(id) {
            return $.ajax({
                url: "{{url('payment-method')}}" + '/' + id,
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                success: async function (data) {
                },
                error: function (error) {
                }
            });
        }

        function setPhoto(ins) {
            const [file] = ins.files
            if (file) {
                var output = document.getElementById('profile-photo');
                output.src = URL.createObjectURL(file);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
                $("#photo-box").show();
            }
        }
    </script>
@stop

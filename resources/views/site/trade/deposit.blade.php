@extends('site.trade.layout.index')

@section('page-title')
    Deposits
@stop

@section('title')
    Make Deposit
@stop

@section('content')
    <div class="card border-0 bg-self px-2">
        <div class="card-body bg-self p-2">
            <form method="post" autocomplete="off" name="deposit-form" id="deposit-form" action="{{url('deposit')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{auth()->id()}}">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label class="form-label required" for="amount">Amount</label>
                        <input type="number" maxlength="11" class="form-control shadow-none" placeholder="0" name="amount" id="amount" required>
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
                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <label class="form-label required" for="bank">Bank</label>
                            <div class="input-group">
                                <input type="text" readonly class="form-control shadow-none bg-secondary" name="bank" id="bank">
                                <span class="input-group-text cursor-pointer bg-transparent copy-bank"
                                      style="border: 1px solid gray; border-radius: 0 !important; cursor: pointer;
                                      border-top-right-radius: 4px !important; border-bottom-right-radius: 4px !important;">
                                    <i class="fal fa-clipboard "></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label class="form-label required" for="account_name">Account Title</label>
                            <div class="input-group">
                                <input type="text" readonly class="form-control shadow-none bg-secondary" name="account_name" id="account_name">
                                <span class="input-group-text cursor-pointer bg-transparent copy-account-name"
                                      style="border: 1px solid gray; border-radius: 0 !important; cursor: pointer;
                                        border-top-right-radius: 4px !important; border-bottom-right-radius: 4px !important;">
                                    <i class="fal fa-clipboard "></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <label class="form-label required" for="account_number">Account Number</label>
                            <div class="input-group">
                                <input type="text" readonly class="form-control shadow-none bg-secondary" name="account_number" id="account_number">
                                <span class="input-group-text cursor-pointer bg-transparent copy-account-number"
                                      style="border: 1px solid gray; border-radius: 0 !important; cursor: pointer;
                                        border-top-right-radius: 4px !important; border-bottom-right-radius: 4px !important;">
                                    <i class="fal fa-clipboard "></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 form-group" id="file-box">
                        <label class="form-label required" for="photo">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" required onchange="setPhoto(this)">
                    </div>
                    <div class="col-md-4 mt-2" id="photo-box">
                        <img src="" width="100%" height="350px" id="profile-photo"
                             style="object-fit: contain;" class="border border-dark p-1 mb-1">
                    </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-success px-4" type="submit" >Deposit</button>
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

        $('.copy-bank').click(function() {
                const textToCopy = $('#bank').val();

                const textarea = document.createElement('textarea');
                textarea.value = textToCopy;
                textarea.style.position = 'fixed';
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                toast('Bank Name copied!', 'success');
        });

        $('.copy-account-name').click(function() {
            const textToCopy = $('#account_name').val();

            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            toast('Account Title copied!', 'success');
        });

        $('.copy-account-number').click(function() {
            const textToCopy = $('#account_number').val();

            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            toast('Account Number copied!', 'success');
        });

    </script>
@stop

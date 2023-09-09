<form action='{{url("/admin/payment-methods/store")}}' method="post" class="form" id="payment-method-form" name="payment-method-form">
    @csrf
    <div class="row">
        <input type="hidden" id="coin_id" name="id" value="{{ @$paymentMethod->id }}">
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="bank" class="form-label">
                        <span class="required">Bank</span>
                    </label>
                    <input type="text" class="form-control" id="bank" name="bank"
                           placeholder="e.g Sadapay" value="{{@$paymentMethod -> bank}}">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="account_title" class="form-label">
                        <span class="required">Account Title</span>
                    </label>
                    <input type="text" class="form-control" id="account_title" name="account_title"
                           placeholder="John Doe" value="{{@$paymentMethod -> account_title}}">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="account_no" class="form-label">
                        <span class="required">Account Number</span>
                    </label>
                    <input type="text" class="form-control" id="account_no" name="account_no"
                           placeholder="34364356573654" value="{{@$paymentMethod->account_no}}">
                </div>
            </div>
        </div>
{{--        <div class="col-lg-12">--}}
{{--            <div class="fv-row">--}}
{{--                <div class="field">--}}
{{--                    <label for="mobile_no" class="form-label">--}}
{{--                        <span class="required">Phone</span>--}}
{{--                    </label>--}}
{{--                    <input type="number" class="form-control" id="mobile_no" name="mobile_no"--}}
{{--                           placeholder="xxxxxxxxxxx" value="{{@$paymentMethod->mobile_no}}">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="field">
                <button type="submit" class="btn btn-sm btn-primary ">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#payment-method-form").validate({
            rules: {
                account_title: {
                    required: true
                },
                account_no: {
                    required: true
                },
                // mobile_no: {
                //     required: true
                // },
                bank: {
                    required: true
                },
            },
            messages: {
                account_no: {
                    required: 'Account number is required'
                },
                account_title: {
                    required: "Account title is required"
                },
                bank: {
                    required: "Bank name is required"
                },
                // mobile_no: {
                //     required: "Mobile number is required"
                // },
            },
            submitHandler: function(form) {
                    return true;
            }
        });
    });
</script>

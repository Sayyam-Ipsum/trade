<form action='{{url("/admin/signals/store")}}' method="post" class="form" id="signal_form" name="signal_form">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="type" class="form-label">
                        <span class="required">Type</span>
                    </label>
                    <select class="form-control" id="type" name="type">
                        <option value="">--Type--</option>
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="start_date_time" class="form-label">
                        <span class="required">Start Date Time</span>
                    </label>
                    <input type="datetime-local" class="form-control" id="start_date_time" name="start_date_time">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="end_date_time" class="form-label">
                        <span class="required">End Date Time</span>
                    </label>
                    <input type="datetime-local" class="form-control" id="end_date_time" name="end_date_time">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="amount" class="form-label">
                        <span class="required">Amount</span>
                    </label>
                    <input type="number" step="any" min="1" class="form-control" id="amount" name="amount">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="field">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#signal_form").validate({
            rules: {
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    required: true
                },
                type: {
                    required: true
                },
                amount: {
                    required: true
                }
            },
            messages: {
                type: {
                    required: 'Please select type*',
                },
                start_date_time: {
                    required: 'Start Date Time is required',
                },
                end_date_time: {
                    required: 'End Date Time is required',
                },
                amount: {
                    required: 'Please Enter Amount*',
                }
            },
            submitHandler: function(form) {
                return true;
            }
        });
    });
</script>

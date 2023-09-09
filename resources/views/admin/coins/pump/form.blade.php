<form action='{{url("/admin/coins/pump-store")}}' method="post" class="form" id="coin_pump_form" name="coin_pump_form">
    @csrf
    <div class="row">
        <input type="hidden" id="coin_id" name="coin_id" value="{{ $coinId }}">
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="pump_type" class="form-label">
                        <span class="required">Pump Type</span>
                    </label>
                    <select class="form-control" id="pump_type" name="pump_type">
                        <option value="">-- Pump Type --</option>
                        <option value="up">Up</option>
                        <option value="down">Down</option>
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
        $("#coin_pump_form").validate({
            rules: {
                start_date_time: {
                    required: true,
                },
                end_date_time: {
                    required: true
                },
                pump_type: {
                    required: true
                }
            },
            messages: {
                pump_type: {
                    required: 'Pump type is required',
                },
                start_date_time: {
                    required: 'Start Date Time is required',
                },
                end_date_time: {
                    required: 'End Date Time is required',
                }
            },
            submitHandler: function(form) {
                return true;
            }
        });
    });
</script>

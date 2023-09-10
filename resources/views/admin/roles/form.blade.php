<form action='{{url("/admin/roles/store")}}' method="post" class="form" id="role-form" name="role-form">
    @csrf
    <div class="row">
        <input type="hidden" id="coin_id" name="id" value="{{ @$role->id }}">
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="name" class="form-label">
                        <span class="required">Name</span>
                    </label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="" value="{{@$role -> name}}">
                </div>
            </div>
        </div>
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
        $("#role-form").validate({
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Name is required"
                }
            },
            submitHandler: function(form) {
                return true;
            }
        });
    });
</script>

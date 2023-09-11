<form action='{{url("/admin/system-users/store")}}/{{@$user->id}}' method="post" class="form" id="user-form" name="user-form">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="name" class="form-label">
                        <span class="required">Name</span>
                    </label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="" value="{{@$user -> name}}">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="email" class="form-label">
                        <span class="required">Email</span>
                    </label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="" value="{{@$user -> email}}">
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="role" class="form-label">
                        <span class="required">Role</span>
                    </label>
                    <select class="form-control" id="role" name="role" {{($editable) ? "disabled" : ''}}>
                        <option value="">---Select---</option>
                        @foreach(@$roles as $role)
                            <option value="{{$role->id}}" {{$role->id == @$user->role_id ? "selected" : ""}}>{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @if($editable)
            <input type="hidden" name="role" value="{{$user->role_id}}">
        @endif
        @if(!$editable)
        <div class="col-lg-12">
            <div class="fv-row">
                <div class="field">
                    <label for="password" class="form-label">
                        <span class="required">Password</span>
                    </label>
                    <input type="text" class="form-control" id="password" name="password"
                           placeholder="" value="" minlength="8" maxlength="8">
                </div>
            </div>
        </div>
        @endif
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
        $("#user-form").validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                },
                role: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "Name is required"
                },
                email: {
                    required: "Email is required"
                },
                role: {
                    required: "Select Role*"
                }
            },
            submitHandler: function(form) {
                return true;
            }
        });
    });
</script>

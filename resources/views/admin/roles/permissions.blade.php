<form name="role-permissions-form" id="role-permissions-form">
    @csrf
    <input type="hidden" name="role_id" value="{{@$role->id}}" >
    <div class="row">
        @foreach($permissions as $permission)
            <div class="col-lg-6 d-flex justify-content-between align-items-center p-2 bg-white shadow-sm mb-2">
                <div class="text-capitalize">
                    <label style="font-weight: normal !important;" for="{{$permission->name}}">{{$permission->name}}</label>
                </div>
                <div>
                    <div class="custom-control custom-switch">
                        <input type="hidden" name="perm_name" value="{{$permission->name}}">
                        <input type="hidden" name="perm_status" value="{{$permission->status}}">
                        @if($permission->status == true)
                            <input class="custom-control-input" type="checkbox"
                                   id="{{$permission->name}}" value="{{$permission->id}}" checked
                                   name="{{$permission->name}}" onchange="getValue(this)">
                            <label class="custom-control-label" for="{{$permission->name}}"></label>
                        @else
                            <input class="custom-control-input" type="checkbox"
                                   id="{{$permission->name}}" value="{{$permission->id}}"
                                   name="{{$permission->name}}" onchange="getValue(this)">
                            <label class="custom-control-label" for="{{$permission->name}}"></label>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</form>

<script>
    function getValue(ctw) {
        $.ajax({
            url: '{{url('admin/roles/change-permission')}}',
            type: "POST",
            data: JSON.stringify({
                _token: "{{ csrf_token() }}",
                role_id: $("input[name='role_id']").val(),
                perm_id: $(ctw).parents("div.custom-switch").children("input[type='checkbox']").val(),
            }),
            processData: false,
            contentType: "application/json; charset=UTF-8",
            cache: false,
            success: function (data){
                toast(data.message, data.success);
            }
        })
    }
</script>

@extends('admin.templates.index')

@section('page-title')
    System Users
@stop

@section('title')
    System Users
@stop

@section('page-actions')
    <a href="javascript:void(0);" class="btn btn-sm btn-add btn-primary">
        <i class="far fa-plus-square mr-1"></i>System User
    </a>
@stop

@section('content')

    <div class="table-responsive my-3">
        <table id="data-table" class="table table-grid table-striped table-sm">
            <thead class="bg-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function (){
            loadTable();

            $("#data-table").on('change', '.btn-status', function() {
                let id = $(this).data('id');
                let status = $("#"+$(this).attr('id')).val();
                $.ajax({
                    url :  '{{url('admin/system-users/status')}}',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "status" : status,
                        "id": id
                    },
                    cache: false,
                    success: function(res) {
                        toast(res.message, res.type);
                        if (res.type == "success") {
                            loadTable();
                        }
                    }
                });
            });
        });

        function loadTable() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {
                    url: "{{url('admin/system-users')}}",
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'role',
                        name: 'role',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable : false
                    }
                ]
            });
        }

        $(".btn-add").click(function (){
            open_modal('{{url('admin/system-users/modal')}}');
        });

        $("#data-table").on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/system-users/modal')}}' + '/' + id);
        });

        $("#data-table").on('click', '.btn-permission', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/system-users/permissions')}}' + '/' + id);
        });
    </script>
@stop

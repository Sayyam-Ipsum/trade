@extends('admin.templates.index')

@section('page-title')
    Users
@stop

@section('title')
    Users
@stop

@section('content')
    <div class="table-responsive">
        <table id="data-table" class="table table-grid table-striped table-sm" style="width: 100%">
            <thead class="bg-light">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Balance</th>
{{--                <th>Actions</th>--}}
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            loadTable();
        });

        function loadTable() {
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                stateSave: true,
                order: [],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {
                    url: "{{url('admin/users')}}"
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
                        data: 'balance',
                        name: 'balance',
                    },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searching: false
                    // }
                ]
            });
        }
    </script>
@stop

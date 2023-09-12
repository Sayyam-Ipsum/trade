@extends('admin.templates.index')

@section('page-title')
    Signals
@stop

@section('title')
    Signals
@stop

@section('page-actions')
    <a href="javascript:void(0);" class="btn btn-sm btn-add btn-primary">
        <i class="far fa-plus-square mr-1"></i>Signal
    </a>
@stop

@section('content')

    <div class="table-responsive my-3">
        <table id="data-table" class="table table-grid table-striped table-sm">
            <thead class="bg-light">
            <tr>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Type</th>
                <th>Amount</th>
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
                    url :  '{{url('admin/roles/status')}}',
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
                    url: "{{url('admin/signals')}}",
                },
                columns: [
                    {
                        data: 'start_time',
                        name: 'start_time',
                    },
                    {
                        data: 'end_time',
                        name: 'end_time',
                    },
                    {
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
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
            open_modal('{{url('admin/signals/modal')}}');
        });
    </script>
@stop

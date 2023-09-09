@extends('admin.templates.index')

@section('page-title')
    Payment Method
@stop

@section('title')
    Payment Method
@stop

@section('page-actions')
    <a href="javascript:void(0);" class="btn btn-sm btn-add btn-primary">
        <i class="far fa-plus-square mr-1"></i>Payment Method
    </a>
@stop

@section('content')

    <div class="table-responsive my-3">
        <table id="data-table" class="table table-grid table-striped table-sm">
            <thead class="bg-light">
            <tr>
                <th>Bank</th>
                <th>Account Title</th>
                <th>Account No</th>
{{--                <th>Mobile No</th>--}}
                <th width="10%">Status</th>
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
                    url :  '{{url('admin/payment-methods/status')}}',
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
                    url: "{{url('admin/payment-methods')}}",
                },
                columns: [
                    {
                        data: 'bank',
                        name: 'bank',
                    }
                    ,{
                        data: 'account_title',
                        name: 'account_title',
                    }
                    ,{
                        data: 'account_no',
                        name: 'account_no',
                    }
                    // ,{
                    //     data: 'mobile_no',
                    //     name: 'mobile_no',
                    // }
                    ,{
                        data: 'status',
                        name: 'status',
                    }
                    ,{
                        data: 'actions',
                        name: 'actions',
                        orderable : false
                    },
                ]
            });
        }

        $(".btn-add").click(function (){
            open_modal('{{url('admin/payment-methods/modal')}}');
        });

        $("#data-table").on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/payment-methods/modal')}}' + '/' + id);
        });
    </script>
@stop

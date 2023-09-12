@extends('admin.templates.index')

@section('page-title')
    Trades
@stop

@section('title')
    Trades
@stop

@section('content')
    <div class="mb-3">
        <form method="post" id="frm_filter" name="frm_filter">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="field mt-0">
                        <label class="form-label">Start Date</label>
                        <input class="form-control form-control-sm" type="date" name="start_date" id="start_date" required>
                    </div>
                    <label id="start_date-error" class="error d-none" for="start_date"></label>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="field mt-0">
                        <label class="form-label">End Date</label>
                        <input class="form-control form-control-sm" type="date" name="end_date" id="end_date" required>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <button type="submit" style="margin-top:32px!important;" class=" btn btn-sm btn-primary" id="btn_filter">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    <a href="javascript:void(0);" style="margin-top:32px!important;" class=" btn btn-sm btn-secondary" id="btn_reset">
                        <i class="fas fa-sync-alt mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table id="data-table" class="table table-grid table-striped table-sm" style="width: 100%">
            <thead class="bg-light">
            <tr>
                <th width="20%">User</th>
                <th>Amount</th>
                <th>Profitable Amount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@stop

@section('scripts')
    <script>
        $("#start_date").val(<?= "'$start_date'" ?>);
        $("#end_date").val(<?= "'$end_date'"?>);

        $(document).ready(function () {
            loadTable();

            $("#data-table").on('click', '.btn-result', function() {
                let id = $(this).data('id');
                let result = $(this).data('result');
                $.ajax({
                    url :  '{{url('admin/trades/result')}}',
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "result": result
                    },
                    cache: false,
                    success: function(res) {
                        toast(res.message, res.status ? "success" : "error");
                        if (res.status) {
                            loadTable();
                        }
                    }
                });
            });

            $("#btn_reset").click(function(){
                $("#start_date").val(<?= "'$start_date'" ?>);
                $("#end_date").val(<?= "'$end_date'"?>);
                loadTable();
            })

            $("#btn_filter").click(function(){
                $("#frm_filter").validate({
                    rules:{
                        start_date: {
                            required:true
                        },
                        end_date: {
                            required:true
                        },
                    },
                    messages:{

                    },
                    submitHandler:function(form){
                        loadTable();
                    }
                });

            });
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
                    url: "{{url('admin/trades')}}",
                    data: function(d){
                        d.start_date = $("#start_date").val(),
                            d.end_date = $("#end_date").val()
                    }
                },
                columns: [
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'profitable_amount',
                        name: 'profitable_amount',
                    },
                    {
                        data: 'type',
                        name: 'type',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                    }
                ]
            });
        }
    </script>
@stop

@extends('admin.global.index')

@section('page-title')
    Trades
@stop

@section('title')
    Trades
@stop

@section('page-actions')
    <button class="btn btn-sm btn-primary btn_balance">Balance</button>
@stop

@section('content')
    <div class="card-body p-0 py-2 shadow-sm">
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label" for="user">User</label>
                <select class="form-control select2" name="user" id="user" onchange="loadTable()">
                    <option value="">--Select--</option>
                    <option value="">All</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div id="table-box">
        <div class="table-responsive">
            <table id="data-table" class="table table-grid table-striped table-sm" style="width: 100%">
                <thead class="bg-light">
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Profit</th>
                    <th>Type</th>
                    <th>Result</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#table-box").hide();

            $(".btn_balance").click(function () {
                let userID = $("#user").val();
                if (userID == '') {
                   toast("Please Select User", "info");
                   return;
                }

                open_modal('{{url('gadmin/balance/modal')}}' + '/' + userID);
            });

            $("#data-table").on('click', '.btn-result', function () {
                let id = $(this).data('id');
                let result = $(this).data('result');
                $.ajax({
                    url :  '{{url('gadmin/update-trade')}}',
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
        });

        function loadTable() {
            if ($("#table-box").hide()) {
                $("#table-box").show()
            }
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
                    url: "{{url('gadmin/trades')}}",
                    data: function(d){
                        d.user_id = $("#user").val()
                    }
                },
                columns: [
                    {
                        data: 'date',
                        name: 'date',
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
                        data: 'result',
                        name: 'result',
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

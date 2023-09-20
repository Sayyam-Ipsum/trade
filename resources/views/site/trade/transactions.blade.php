@extends('site.trade.layout.index')

@section('page-title')
    Transactions
@stop

@section('title')
    Transactions
@stop

@section('content')
    <div class="row m-0">
        <div class="mt-3 col-md-6 p-0 p-1">
            <div class="card border-0">
                <div class="card-header bg-secondary">
                    <h6 class="m-0 text-white" style="font-family: med;">Deposit History</h6>
                </div>
                <div class="card-body bg-self border border-dark p-2">
                    <div class="table-responsive p-0">
                        <table class="table table-sm data-table" id="deposit-data-table">
                            <thead>
                            <tr>
                                <th width="35%">Date</th>
                                <th width="35%">Amount</th>
                                <th width="30%">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3 col-md-6 p-0 p-1">
            <div class="card border-0">
                <div class="card-header bg-secondary">
                    <h6 class="m-0 text-white" style="font-family: med;">Withdrawal History</h6>
                </div>
                <div class="card-body bg-self border border-dark p-2">
                    <div class="table-responsive p-0">
                        <table class="table table-sm data-table" id="withdrawal-data-table">
                            <thead class="">
                            <tr>
                                <th width="35%">Date</th>
                                <th width="35%">Amount</th>
                                <th width="30%">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#deposit-data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [[ 0, "desc" ]],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: { url: "{{url('deposit')}}" },
                columns: [
                    { data: 'date', name: 'date' },
                    { data: 'amount', name: 'amount' },
                    { data: 'status', name: 'status' },
                ]
            });

            $('#withdrawal-data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [[0, "desc"]],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {url: "{{url('withdrawal')}}"},
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                ]
            });
        });
    </script>

@stop

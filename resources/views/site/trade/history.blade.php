@extends('site.trade.layout.index')

@section('page-title')
    Trading History
@stop

@section('title')
    Trading History
@stop

@section('content')
    <div class="mt-3">
        <div class="card border-0">
            <div class="card-header bg-secondary">
                <h6 class="m-0 text-white" style="font-family: med;">Trading History</h6>
            </div>
            <div class="card-body bg-self border border-dark">
                <div class="table-responsive p-0">
                    <table class="table table-sm" id="history-data-table">
                        <thead>
                        <tr>
                            <th width="20%">Date</th>
                            <th width="20%">Amount Invested</th>
                            <th width="20%">Trade</th>
                            <th width="20%">Status</th>
                            <th width="20%">Result</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#history-data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [[0, "desc"]],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {url: "{{url('trade-history')}}"},
                columns: [
                    {data: 'created_at', name: 'created_at'},
                    {data: 'amount', name: 'amount'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'result', name: 'result'}
                ]
            });
        });
    </script>

@stop

@extends('site.trade.layout.index')

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
                    <table class="table table-sm table-dark table-striped table-hover" id="trading-data-table">
                        <thead class="">
                        <tr>
                            <th width="20%">Coin</th>
                            <th width="20%">Amount Invested</th>
                            <th width="20%">Starting Price</th>
                            <th width="20%">Closing Price</th>
                            <th width="20%">Time Period</th>
                            <th width="20%">Type</th>
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
            $('#trading-data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [[0, "desc"]],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {url: "{{url('trading/history').'/'}}"},
                columns: [
                    {data: 'coin', name: 'coin'},
                    {data: 'amount_invested', name: 'amount_invested'},
                    {data: 'starting_price', name: 'starting_price'},
                    {data: 'closing_price', name: 'closing_price'},
                    {data: 'time_period', name: 'time_period'},
                    {data: 'type', name: 'type'},
                    {data: 'result', name: 'result'}
                ]
            });
        });
    </script>

@stop

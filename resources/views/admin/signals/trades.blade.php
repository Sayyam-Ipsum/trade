@extends('admin.templates.index')

@section('page-title')
    Signal {{showTime(@$signal->start_time)}} - {{showTime(@$signal->end_time)}}
@stop

@section('title')
    Signal {{showTime(@$signal->start_time)}} - {{showTime(@$signal->end_time)}}
    @if(@$signal->result != "none")
        <span class="badge badge-{{@$signal->result == "profit" ? "success" : "danger"}} text-capitalize" style="font-weight: normal !important; font-size: 16px !important;">
            {{@$signal->result}}
        </span>
    @endif
@stop

@section('content')
    <div class="row">

{{--        <div class="col-md-4 mt-2">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body border border-{{@$signal->type == "buy" ? "success" : "danger"}} rounded">--}}
{{--                    <div class="d-flex justify-content-between align-items-center">--}}
{{--                        <p class="m-0">Signal Type</p>--}}
{{--                        <span class="badge badge-{{@$signal->type == "buy" ? "success" : "danger"}} text-capitalize">--}}
{{--                            {{@$signal->type}}--}}
{{--                        </span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @include('partials.signal-detail-card', [--}}
{{--            'title' => 'Signal Amount',--}}
{{--            'data' => '$'.@$signal->amount,--}}
{{--            'border' => "info"--}}
{{--        ])--}}

        @include('partials.signal-detail-card', [
            'title' => 'Buy Trades',
            'data' => @$data['buy_trades_total'],
            'border' => "dark"
        ])

        @include('partials.signal-detail-card', [
            'title' => 'Buy Trades Total',
            'data' => '$'.@$data['buy_trades_sum'],
            'border' => "warning"
        ])

        @include('partials.signal-detail-card', [
            'title' => 'Sell Trades',
            'data' => @$data['sell_trades_total'],
            'border' => "secondary"
        ])

        @include('partials.signal-detail-card', [
            'title' => 'Sell Trades Total',
            'data' => '$'.@$data['sell_trades_sum'],
            'border' => "primary"
        ])
    </div>
    <div class="table-responsive">
        <table id="data-table" class="table table-grid table-striped table-sm" style="width: 100%">
            <thead class="bg-light">
            <tr>
                <th width="20%">User</th>
                <th>Amount</th>
                <th>Profitable Amount</th>
                <th>Type</th>
                <th>Result</th>
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
                    url: "{{url('admin/signals').'/'.@$signal->id}}",
                    data: function(d){
                        d.start_date = ''
                            d.end_date = ''
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
                        data: 'result',
                        name: 'result',
                    }
                ]
            });
        }
    </script>
@stop

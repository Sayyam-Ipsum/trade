@extends('admin.templates.index')

@section('page-title')
    Live Trading
@stop

@section('title')
    Live Trading
@stop

@section('page-actions')
    <strong>{{showDate(date('Y-m-d'))}}</strong>
@stop

@section('content')
    {{--    @if(count($signals) > 0)--}}
    <div class="row" id="signals-box"></div>
    {{--            @foreach($signals as $signal)--}}
    {{--                <div class="col-md-3 signal-box">--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-body p-0">--}}
    {{--                            <p class="text-center pt-2">{{showTime($signal->start_time)}} - {{showTime($signal->end_time)}}</p>--}}
    {{--                            <div class="d-flex justify-content-between align-items-center px-2 py-1">--}}
    {{--                                {!! statusBadge($signal->type) !!}--}}
    {{--                                <div>--}}
    {{--                                    <span><small>Signal:</small><span><b>${{$signal->amount}}</b></span></span>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            @if($signal->trades['buy_trades_total'] == 0 && $signal->trades['sell_trades_total'] == 0)--}}
    {{--                                <div class="text-center text-danger px-2 py-1">--}}
    {{--                                    <span>No trade exists for this signal.</span>--}}
    {{--                                </div>--}}
    {{--                            @else--}}
    {{--                                <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">--}}
    {{--                                    <span class="text-capitalize">Buy Trades: ({{$signal->trades['buy_trades_total']}})</span>--}}
    {{--                                    <span><b>${{$signal->trades['buy_trades_sum']}}</b></span>--}}
    {{--                                </div>--}}
    {{--                                <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">--}}
    {{--                                    <span class="text-capitalize">Sell Trades: ({{$signal->trades['sell_trades_total']}})</span>--}}
    {{--                                    <span><b>${{$signal->trades['sell_trades_sum']}}</b></span>--}}
    {{--                                </div>--}}

    {{--                                <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">--}}
    {{--                                    <span class="text-capitalize">Buy Trades Overprice:</span>--}}
    {{--                                    <span><b>${{$signal->trades['buy_trades_overprice']}}</b></span>--}}
    {{--                                </div>--}}
    {{--                                <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">--}}
    {{--                                    <span class="text-capitalize">Sell Trades Overprice:</span>--}}
    {{--                                    <span><b>${{$signal->trades['sell_trades_overprice']}}</b></span>--}}
    {{--                                </div>--}}

    {{--                                @if($signal->status === "in-progress")--}}
    {{--                                    <div class="d-flex justify-content-center align-items-center py-1">--}}
    {{--                                        <small class="mr-2">Select result:</small>--}}
    {{--                                        <div class="form-check form-check-inline">--}}
    {{--                                            <input class="form-check-input" type="radio" name="type-{{$signal->id}}"--}}
    {{--                                                   id="buy-{{$signal->id}}" value="buy">--}}
    {{--                                            <label class="form-check-label" for="buy-{{$signal->id}}">Buy</label>--}}
    {{--                                        </div>--}}
    {{--                                        <div class="form-check form-check-inline">--}}
    {{--                                            <input class="form-check-input" type="radio" name="type-{{$signal->id}}"--}}
    {{--                                                   id="sell-{{$signal->id}}" value="sell">--}}
    {{--                                            <label class="form-check-label" for="sell-{{$signal->id}}">Sell</label>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="d-flex justify-content-between align-items-center px-2 py-1">--}}
    {{--                                        <button type="button" class="btn btn-sm btn-success btn-result w-50 mr-1" data-type="profit"--}}
    {{--                                                data-id="{{$signal->id}}">Profit</button>--}}
    {{--                                        <button type="button" class="btn btn-sm btn-danger btn-result w-50" data-type="loss"--}}
    {{--                                                data-id="{{$signal->id}}">Loss</button>--}}
    {{--                                    </div>--}}
    {{--                                @else--}}
    {{--                                    <div class="d-flex justify-content-between align-items-center px-2 py-1">--}}
    {{--                                        <span><small class="mr-1">Result:</small>{!! statusBadge($signal->result) !!}</span>--}}
    {{--                                        <a class="link link-primary text-decoration-underline" target="_blank" href="{{url('admin/signals').'/'.$signal->id}}">view details</a>--}}
    {{--                                    </div>--}}
    {{--                                @endif--}}
    {{--                            @endif--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            @endforeach--}}
    {{--        </div>--}}
    {{--    @else--}}
    {{--        <div class="text-center text-danger pt-3">--}}
    {{--            No Signal added for today!--}}
    {{--        </div>--}}
    {{--    @endif--}}
@stop

@section('scripts')
    <script>

        $(document).ready(async function () {

            getSignals();

            $("#signals-box").on('click', '.btn-result', function () {
                let type = $(this).data('type');
                let signalID = $(this).data('id');
                let result = $(`input[name='type-${signalID}']:checked`).val();

                if (result === undefined) {
                    toast("Please select result type", "info");
                    return;
                }

                $.ajax({
                    url: '{{url('admin/trading/store')}}',
                    type:
                        "POST",
                    data:
                        JSON.stringify({
                            _token: "{{ csrf_token() }}",
                            type: type,
                            signal_id: signalID,
                            result: result
                        }),
                    processData:
                        false,
                    contentType:
                        "application/json; charset=UTF-8",
                    cache:
                        false,
                    success:

                        function (data) {
                            toast(data.message, data.status ? "success" : "error");
                            if (data.status) {
                                getSignals();
                                // window.location.reload();
                            }
                        }
                })
            });

            setInterval(getSignals, 10000);

        });

        function getSignals() {
            $.ajax({
                url: "{{url('admin/trading')}}",
                type: "GET",
                cache: false,
                processData: false,
                contentType: "application/json; charset=UTF-8",
                success: function (res) {
                    buildSignalsHtml(res.data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        function buildSignalsHtml(data) {
            $('#signals-box').html(html);
            var html = ``;
            if (data.length > 0) {
                for (let i = 0; i < data.length; i++) {
                    html += `<div class="col-md-3 signal-box">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <p class="text-center pt-2">${data[i].start_time} - ${data[i].end_time}</p>
                                        <div class="d-flex justify-content-between align-items-center px-2 py-1">
                                            <div>
                                            </div>
                                        </div>`;
                                        if (data[i].trades.buy_trades_total == 0 && data[i].trades.buy_trades_total == 0) {
                                            html += `<div class="text-center text-danger px-2 py-1">
                                                        <span>No trade exists for this signal.</span>
                                                     </div>`;
                                        } else {
                                            html += `<div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">
                                                        <span class="text-capitalize">Buy Trades: (${data[i].trades.buy_trades_total})</span>
                                                        <span><b>$${data[i].trades.buy_trades_sum}</b></span>
                                                     </div>
                                                     <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">
                                                        <span class="text-capitalize">Sell Trades: (${data[i].trades.sell_trades_total})</span>
                                                        <span><b>$${data[i].trades.sell_trades_sum}</b></span>
                                                     </div>`;
                                        }

                                        if ((data[i].trades.buy_trades_total > 0 || data[i].trades.buy_trades_total > 0) && data[i].result == "none") {
                                            html += `<div class="d-flex justify-content-center align-items-center py-1">
                                                        <small class="mr-2">Select result:</small>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="type-${data[i].id}" id="buy-${data[i].id}" value="buy">
                                                            <label class="form-check-label" for="buy-${data[i].id}">Buy</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="type-${data[i].id}" id="sell-${data[i].id}" value="sell">
                                                            <label class="form-check-label" for="sell-${data[i].id}">Sell</label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center px-2 py-1">
                                                        <button type="button" class="btn btn-sm btn-success btn-result w-50 mr-1" data-type="profit"
                                                            data-id="${data[i].id}">Profit</button>
                                                        <button type="button" class="btn btn-sm btn-danger btn-result w-50" data-type="loss"
                                                            data-id="${data[i].id}">Loss</button>
                                                        </div>`;
                                        }

                                        if (data[i].trades.buy_trades_total > 0 && data[i].trades.buy_trades_total > 0) {
                                            html += `<div class="d-flex justify-content-between align-items-center px-2 py-1">
                                                    <span><small class="mr-1">Result:</small><span class="badge badge-${data[i].result === 'profit' ? 'success' : 'danger'} p-1 text-capitalize">${data[i].result}</span></span>
                                                    <a class="link link-primary text-decoration-underline" target="_blank" href="{{url('admin/signals')}}/${data[i].id}">view details</a>
                                                </div>`;
                                        }
                            html += `</div></div></div>`;
                }
            } else {
                html += `<div class="text-center text-danger pt-3">No Signal added for today!</div>`;
            }

            $('#signals-box').html(html);
        }
    </script>
@stop

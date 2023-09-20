@extends('site.trade.layout.index')

@section('page-title')
    Market
@stop

@section('title')
    Market
@stop

@section('content')
    <div class="row mt-3">
        <div class="col-md-9">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
                <div id="tradingview_ca040"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                <script type="text/javascript">
                    new TradingView.widget(
                        {
                            "width": '100%',
                            "height": 720,
                            "autosize": false,
                            "symbol": "BINANCE:BTCUSDT",
                            "interval": "5",
                            "timezone": "Etc/UTC",
                            "theme": "dark",
                            "style": "1",
                            "locale": "en",
                            "enable_publishing": false,
                            "allow_symbol_change": true,
                            "container_id": "tradingview_ca040"
                        }
                    );
                </script>
            </div>
            <!-- TradingView Widget END -->
        </div>
        <div class="col-md-3">
            <div class="card-body border border-secondary rounded p-0">
                <div class="p-2">
                    <div class="text-right">
                        <span id="timer"></span>
                    </div>
                    <input type="hidden" name="profitable-amount" id="profitable-amount" value="">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" step="any" class="form-control" name="amount" id="amount" min="1">
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success w-50 mr-1 btn-trade" data-type="buy">Buy</button>
                        <button class="btn btn-danger w-50 btn-trade" data-type="sell">Sell</button>
                    </div>
                </div>

                <hr style="background: gray; margin: 0; margin: 5px 0px;">
                <div class="px-2">
                    <p class="m-0" style="font-family: bold">Recent Trades</p>
                </div>
                <hr style="background: gray; margin: 0; margin: 5px 0px;">
                <div class="p-0">
                    <table class="table p-0">
                        <tr class="border-0">
                            <th class="text-center border-0" style="border-bottom: 1px solid gray !important;" width="35%">Amount</th>
                            <th class="text-center border-0" style="border-bottom: 1px solid gray !important;" width="30%">Trade</th>
                            <th class="text-center border-0" style="border-bottom: 1px solid gray !important;" width="35%">Result</th>
                        </tr>
                        <tbody id="trades-box" class=" border-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        var end_time, signal_id;
        var canTrade = true;
        $(document).ready(function () {
            getSignal();
            buildTrades();
            $("#amount").on('keyup', function () {
                let amount = Number($("#amount").val());
                let user_balance = Number({{auth()->user()->account_balance}});

                if (user_balance < amount) {
                    $("#profitable-amount").val(0);
                    toast("You don't have enough balance. Please deposit money", "warning");
                    return;
                }

                let profit_percent = 95;
                let profit = (Number(amount) * (Number(profit_percent) / 100)).toFixed(2);
                let profitable_amount = (Number(amount) + Number(profit)).toFixed(2);
                $("#profitable-amount").val(profitable_amount);
            });

            $(".btn-trade").on('click', function () {
                let amount = Number($("#amount").val());
                let profitable_amount =  $("#profitable-amount").val();

                if (amount == '') {
                    toast("Please enter amount", "warning");
                    return;
                }

                if (amount < 1) {
                    toast("Please enter amount greater than 0", "warning");
                    return;
                }

                if (!canTrade) {
                    toast("Time exceeded", "warning");
                    return;
                }

                let type = $(this).data('type');
                let user_id = {{auth()->user()->id}};

                $.ajax({
                    url: '{{url('trade')}}',
                    type: "POST",
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        user_id: user_id,
                        signal_id: signal_id,
                        type: type,
                        amount: amount,
                        profitable_amount: profitable_amount
                    }),
                    processData: false,
                    contentType: "application/json; charset=UTF-8",
                    cache: false,
                    success: function (data){
                        toast(data.message, data.status ? "success" : "error");
                        if (data.status) {
                            $("#amount").val('');
                            $("#profitable-amount").val('');
                            setAccountBalance();
                            buildTrades();
                        }
                    }
                });
            });
        });

        async function buildTrades() {
            $("#trades-box").html('');
            let res = await getTrades();
            let trades = res.data;
            let html = "";

            if (trades.length > 0) {
                for (let i=0; i<trades.length; i++) {
                    let result = '-';
                    if (trades[i].result == "profit") {
                        result = `<i class="far fa-plus" style="font-size: 12px;"></i>$${trades[i].profitable_amount}<span class="ml-1 text-success">Profit</span>`;
                    }
                    if (trades[i].result == "loss") {
                        result = `<i class="far fa-minus" style="font-size: 12px;"></i>$${trades[i].amount}<span class="ml-1 text-danger">Loss</span>`;
                    }
                    if (trades[i].status == "in-progress") {
                        result = `<span class="badge badge-primary">${trades[i].status}</span>`;
                    }
                    html += `<tr>
                                <td class="text-center border-0" style="border-bottom: 1px dashed gray !important;" width="35%"><small>$${trades[i].amount}</small></t>
                                <td class="text-center border-0" style="border-bottom: 1px dashed gray !important;" width="30%"><small class="text-capitalize">${trades[i].type}</small></t>
                                <td class="text-center border-0" style="border-bottom: 1px dashed gray !important;" width="35%">${result}</td>
                            </tr>`;
                }
            } else {
                html += `<tr><td colspan="3" class="text-center border-0">No Trades</td></tr>`;
            }

            $("#trades-box").append(html);
        }

        function timer() {
            var update = setInterval(function () {
                var now = new Date().getTime();
                var diff = end_time - now;
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                if (minutes < 0 && seconds < 0) {
                    clearInterval(update);
                    getSignal();
                }

                if (minutes < 1 && seconds <= 59) {
                    canTrade = false;
                }

                let html = `<small>0${minutes}:${seconds<10?0:''}${seconds}</small>`;
                $("#timer").html(html);
                $("#timer").css('color', "#ffffff");
                $("#time").show();
            }, 1000);
        }

        function getSignal() {
            $.ajax({
                url: "{{url('signal')}}",
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                success: function (res) {
                    if (res.status) {
                        let signal = res.signal;
                        signal_id = signal.id;
                        end_time = new Date(signal.end_time).getTime();
                        canTrade = true;
                        timer();
                    }
                },
                error: function (error) {
                }
            });
        }

        function getTrades() {
            return $.ajax({
                url: "{{url('trades/list/today')}}",
                type: 'GET',
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                success: async function (data) {
                },
                error: function (error) {
                }
            });
        }
    </script>
@stop

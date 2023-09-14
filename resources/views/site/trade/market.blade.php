@extends('site.trade.layout.index')

@section('page-title')
    Market
@stop

@section('title')
    Market
@stop

@section('content')
    <div class="row mt-3">
        <div class="col-md-9 p-0 px-3" style="height: 600px;">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
                <div id="tradingview_f5eb5"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                <script type="text/javascript">
                    new TradingView.widget(
                        {
                            "width": '100%',
                            "height": 720,
                            "autosize": false,
                            "symbol": "BITSTAMP:BTCUSD",
                            "interval": "5",
                            "timezone": "Etc/UTC",
                            "theme": "dark",
                            "style": "1",
                            "locale": "en",
                            "enable_publishing": false,
                            "backgroundColor": "rgba(0, 0, 0, 1)",
                            "hide_side_toolbar": false,
                            "allow_symbol_change": true,
                            "details": true,
                            "studies": [
                                "STD;24h%Volume"
                            ],
                            "container_id": "tradingview_f5eb5"
                        }
                    );
                </script>
            </div>
            <!-- TradingView Widget END -->
        </div>
        <div class="col-md-3 p-0 pr-3">
            <div class="card-body border border-secondary rounded p-0">
                <div class="p-2">
                    <input type="hidden" name="profit" id="profit" value="">
                    <div class="form-group">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="number" step="any" class="form-control" name="amount" id="amount" min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="profitable-amount">Profit</label>
                        <input type="text" class="form-control bg-secondary" name="profitable-amount" id="profitable-amount" value="" readonly>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success w-50 mr-1 btn-trade" data-type="buy">95% Buy</button>
                        <button class="btn btn-danger w-50 btn-trade" data-type="sell">95% Sell</button>
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
        $(document).ready(function () {
            buildTrades();
            $("#amount").on('keyup', function () {
                let amount = Number($("#amount").val());
                let user_balance = Number({{auth()->user()->account_balance}});

                if (user_balance < amount) {
                    $("#profit").val(0);
                    $("#profitable-amount").val(0);
                    toast("You don't have enough balance. Please deposit money", "warning");
                    return;
                }
                let profit_percent = 95;
                let profit = (Number(amount) * (Number(profit_percent) / 100)).toFixed(2);
                let profitable_amount = (Number(amount) + Number(profit)).toFixed(2);
                $("#profit").val(profit);
                $("#profitable-amount").val(profitable_amount);
            });

            $(".btn-trade").on('click', function () {
                let amount = Number($("#amount").val());
                let profit = Number($("#profit").val());
                let profitable_amount =  $("#profitable-amount").val();

                if (amount == '') {
                    toast("Please enter amount", "warning");
                    return;
                }
                if (amount < 1) {
                    toast("Please enter amount greater than 0", "warning");
                    return;
                }
                if (profit < 0) {
                    toast("Please enter valid amount", "warning");
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
                            $("#profit").val('');
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
            console.log(trades);
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
                        result = `<span id="timer-${trades[i].id}"></span>`;
                        runTimer(trades[i].id);
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

        function runTimer(id) {
            let seconds = 5 * 60;
            let count = new Date();
            count.setSeconds(count.getSeconds() + seconds);
            var countDown = count.getTime();
            var update = setInterval(function () {
                var now = new Date().getTime();
                var diff = countDown - now;
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);

                let html = `<small>Time: 0${minutes}: ${seconds}</small>`;
                $(`#timer-${id}`).html(html);
                $(`#timer-${id}`).css('color', "#ffffff");
                $("#time").show();
                if (diff < 0) {
                    clearInterval(update);
                }
            }, 1000);
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

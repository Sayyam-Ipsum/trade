@extends('site.trade.layout.index')

@section('page-title')
    Market
@stop

@section('title')
    Market
@stop

@section('content')
    <div class="row mt-3">
        <div class="col-md-10 p-0 px-3" style="height: 600px;">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
                <div id="tradingview_f5eb5"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/" rel="noopener nofollow" target="_blank"><span class="blue-text">Track all markets on TradingView</span></a></div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                <script type="text/javascript">
                    new TradingView.widget(
                        {
                            "width": '100%',
                            "height": 600,
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
        <div class="col-md-2 p-0 pr-3">
            <div class="card-body p-0 p-2 bg-self border border-secondary rounded">
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
        </div>

    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
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
                        }
                    }
                });
            });
        });
    </script>
@stop

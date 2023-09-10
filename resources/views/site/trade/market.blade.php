@extends('site.trade.layout.index')

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
            <div class="card-body p-0 p-2 bg-self border border-secondary">
                <div class="form-group">
                    <label class="form-label" for="amount">Amount</label>
                    <input type="number" class="form-control" name="amount" id="amount" min="1">
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-success w-50 mr-1">Buy</button>
                    <button class="btn btn-danger w-50">Sell</button>
                </div>
            </div>
        </div>

    </div>
@stop

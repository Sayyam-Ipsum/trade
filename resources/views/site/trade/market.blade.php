@extends('site.trade.layout.index')

@section('title')
    Market
@stop

@section('content')
    <div class="row mt-3">
        <div class="col-md-10">
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
        <div class="col-md-2">
        </div>
    </div>
@stop

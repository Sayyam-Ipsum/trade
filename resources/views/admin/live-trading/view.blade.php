@extends('admin.templates.index')

@section('page-title')
    Live Trading
@stop

@section('title')
    Live Trading
@stop

@section('page-actions')
    <a href="javascript:void(0);" class="btn btn-add btn-sm btn-primary">
        <i class="far fa-eye mr-1"></i>View All
    </a>
@stop

@section('content')
    @if(count($signals) > 0)
        <div class="row" id="signals-box">
            @foreach($signals as $signal)
                <div class="col-md-3 signal-box">
                    <div class="card">
                        <div class="card-body p-0">
                            <p class="text-center pt-2">{{showTime($signal->start_time)}} - {{showTime($signal->end_time)}}</p>
                            <div class="d-flex justify-content-between align-items-center px-2 py-1">
                                {!! statusBadge($signal->type) !!}
                                <div>
                                    <span><small>Signal:</small><span><b>${{$signal->amount}}</b></span></span>
                                </div>
                            </div>
                            @if(count($signal->trades) > 0)
                                @foreach($signal->trades as $trade)
                                    <div class="d-flex justify-content-between align-items-center px-2 py-1 border-bottom border-muted">
                                        <span class="text-capitalize">{{$trade->type}} Trades: ({{$trade->trades_count}})</span>
                                        <span><b>${{$trade->trades_sum}}</b></span>
                                    </div>
                                @endforeach

                                @if($signal->status === "in-progress")
                                    <div class="d-flex justify-content-center align-items-center py-1">
                                        <small class="mr-2">Select result:</small>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type-{{$signal->id}}"
                                                   id="buy-{{$signal->id}}" value="buy">
                                            <label class="form-check-label" for="buy-{{$signal->id}}">Buy</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type-{{$signal->id}}"
                                                   id="sell-{{$signal->id}}" value="sell">
                                            <label class="form-check-label" for="sell-{{$signal->id}}">Sell</label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center px-2 py-1">
                                        <button type="button" class="btn btn-sm btn-success btn-result w-50 mr-1" data-type="profit"
                                                data-id="{{$signal->id}}">Profit</button>
                                        <button type="button" class="btn btn-sm btn-danger btn-result w-50" data-type="loss"
                                                data-id="{{$signal->id}}">Loss</button>
                                    </div>
                                @else
                                    <div class="text-center text-success px-2 py-1">
                                        <span>Signal Completed</span>
                                    </div>
                                @endif
                            @else
                                <div class="text-center text-danger px-2 py-1">
                                    <span>No trade exists for this signal.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-danger pt-3">
            No Signal added for today!
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $(document).ready(function (){
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
                    type: "POST",
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        type: type,
                        signal_id: signalID,
                        result: result
                    }),
                    processData: false,
                    contentType: "application/json; charset=UTF-8",
                    cache: false,
                    success: function (data){
                        toast(data.message, data.status ? "success" : "error");
                        if (data.status) {
                            window.location.reload();
                        }
                    }
                })
            });
        });
    </script>
@stop

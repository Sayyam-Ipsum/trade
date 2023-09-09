<div>
    @if(!$pump)
        <div class="alert alert-warning" role="alert">
            No pump is active at the moment for this coin!
        </div>
    @else
    <table class="table table-striped">
        <tr>
            <th colspan="2">Coin</th>
            <th>Pump Type</th>
            <th>Start Date Time</th>
            <th>End Date Time</th>
        </tr>
        <tr>
            <td colspan="2">{{$pump->coin->name}}</td>
            <td>{{$pump->pump_type}}</td>
            <td>{{showDateTime($pump->start_date_time)}}</td>
            <td>{{showDateTime($pump->end_date_time)}}</td>
        </tr>
    </table>
    @endif
</div>

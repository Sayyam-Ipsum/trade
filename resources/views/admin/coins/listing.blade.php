@extends('admin.templates.index')

@section('page-title')
    Coins
@stop

@section('title')
    Coins
@stop

@section('page-actions')
    <a href="javascript:void(0);" class="btn btn-sm btn-add btn-primary">
        <i class="far fa-plus-square mr-1"></i>Coin
    </a>
@stop

@section('content')

    <div class="table-responsive my-3">
        <table id="data-table" class="table table-grid table-striped table-sm">
            <thead class="bg-secondary">
            <tr>
                <th width="20%">Name</th>
                <th width="10%">Price</th>
                <th width="15%">Minimum Price</th>
                <th width="15%">Maximum Price</th>
                <th width="10%">Buy Profit (%)</th>
                <th width="10%">Sell Profit (%)</th>
                <th width="20%">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

@stop

@section('scripts')
    <script>
        $(document).ready(function (){
            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                aaSorting: [],
                columnsDefs: [{
                    orderable: true
                }],
                ajax: {
                    url: "{{url('admin/coins')}}",
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'price',
                        name: 'price',
                    },
                    {
                        data: 'min_price',
                        name: 'min_price',
                    },
                    {
                        data: 'max_price',
                        name: 'max_price',
                    },
                    {
                        data: 'buy_profit',
                        name: 'buy_profit',
                    },
                    {
                        data: 'sell_profit',
                        name: 'sell_profit',
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable : false
                    }
                ]
            });
        });

        $(".btn-add").click(function (){
            open_modal('{{url('admin/coins/coin-modal')}}');
        });

        $("#data-table").on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/coins/coin-modal')}}' + '/' + id);
        });

        $("#data-table").on('click', '.btn-create-pump', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/coins/pump-modal')}}' + '/' + id);
        });

        $("#data-table").on('click', '.btn-view-pump', function() {
            var id = $(this).data('id');
            open_modal('{{url('admin/coins/view-pump-modal')}}' + '/' + id);
        });
    </script>
@stop

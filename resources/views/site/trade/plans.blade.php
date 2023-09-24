@extends('site.trade.layout.index')

@section('page-title')
    Plans
@stop

@section('title')
    Plans
@stop

@section('content')
    <form method="post" autocomplete="off" name="plans-form" id="plans-form">
        <div class="row m-0 px-1">
            <div class="col-md-12 p-0">
                <span class="">Calculate your investment plan</span>
            </div>
            <div class="col-md-3 p-0 p-1">
                <div class="form-group">
                    <label class="form-label">Amount</label>
                    <input type="number" placeholder="0" class="form-control" name="amount" id="amount" required>
                </div>
            </div>
            <div class="col-md-4 p-0 p-1">
                <div class="form-group">
                    <label class="form-label m-0 mb-1 d-block calculate-label" style="visibility: hidden">calculate</label>
                    <button class="btn btn-success px-4" type="submit">Calculate</button>
                </div>
            </div>
        </div>
    </form>
    <div class="px-2" id="plans-box">
        <div class="table-responsive p-0">
            <table class="table table-sm data-table">
                <thead>
                <tr>
                    <th width="15%" class="text-center">Stage</th>
                    <th width="25%" class="text-center">Buy</th>
                    <th width="30%" class="text-center">Percentage</th>
                    <th width="30%" class="text-center">Earnings</th>
                </tr>
                </thead>
                <tbody class="plan-table">
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        let arr = [0.336, 0.669, 1.347, 2.75, 5.965, 13.429, 32.318, 99.956];
        $("#plans-box").hide();
        $("#plans-form").validate({
            rules: {
                amount: {
                    required:true,
                    min: 1
                }
            },
            messages: {
                amount: {
                    required:"Please enter amount*",
                    min: "Amount must be greater then 0"
                },
            },
            submitHandler:function (form) {
                let amount = $("#amount").val();
                createPlan(amount);
            }
        });

        function createPlan(amount) {
            $(".plan-table").html('');
            let html = '';
            arr.map((val, key) => {
                let buyAmount = (Number(amount) * (Number(val) / 100)).toFixed(1);
                let earning = (Number(buyAmount) * (Number(95) / 100)).toFixed(3);

                html += `<tr>
                            <td class="text-center">${key+1}</td>
                            <td class="text-center">${buyAmount}</td>
                            <td class="text-center">${val}%</td>
                            <td class="text-center">${earning}</td>
                        </tr>`;
            });

            $(".plan-table").html(html);
            $("#plans-box").show();
        }
        // copy referral link to clipboard

        $(".referral-link").on('click', function () {

            const textToCopy = $(this).data('link');

            const textarea = document.createElement('textarea');
            textarea.value = textToCopy;
            textarea.style.position = 'fixed';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            toast('Link copied to clipboard', 'success');
        });
    </script>
@stop

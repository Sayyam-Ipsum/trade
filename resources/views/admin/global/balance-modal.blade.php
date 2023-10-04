<form method="post" action="{{url('gadmin/update-balance')}}" name="balance-form" id="balance-form">
    @csrf
    <input type="hidden" name="user_id" value="{{@$id}}">
    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="balance">Balance</label>
            <input type="text" class="form-control" name="balance" id="balance" value="${{@$balance}}" readonly>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label class="form-label" for="amount">Amount</label>
            <input type="number" step="any" class="form-control" name="amount" id="amount" value="" placeholder="Amount" required min="1">
        </div>
    </div>

    <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

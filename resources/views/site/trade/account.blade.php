@extends('site.trade.layout.index')

@section('title')
    My Account
@stop

@section('content')
    <div class="card border-0">
        <div class="card-body bg-self shadow-sm p-0 px-2 py-3">
            <div class="row">
                <div class="col-md-4 mt-2" style="border-right: 1px dashed #6c757d;">
                    <h5 style="font-family: bold;" class="text-white m-0">Personal Info:</h5>
                    <div class="mt-3">
                        <form method="post" name="user-form" id="user-form" enctype="multipart/form-data" action="{{url('profile')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{auth()->user()->id}}">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 text-center mt-1">
                                    @php
                                        $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                                    @endphp
                                    <img src="{{$url}}" width="130px" height="130px" id="profile-photo"
                                         style="object-fit: cover;object-position: top;border-radius: 50%;"
                                         class="border border-muted p-1 shadow">
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="name">Name</label>
                                        <input type="text" class="form-control shadow-none" name="name" id="name"
                                               placeholder="e.g Tim David" value="{{auth()->user()->name}}" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="email">Email</label>
                                        <input type="email" class="form-control shadow-none" name="email" id="email"
                                               placeholder="xxxxxxxx@gmail.com" value="{{auth()->user()->email}}" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label" for="photo">Photo</label>
                                        <input type="file" class="form-control" name="photo" id="photo" onchange="setPhoto(this)">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div>
                                        <button class="btn btn-success px-4" style="font-family: med;" type="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4 mt-2" style="border-right: 1px dashed #6c757d;">
                    <h5 style="font-family: bold;" class="text-white m-0">Security:</h5>
                    <div class="mt-3">
                        <form method="post" name="change-password-form" id="change-password-form" action="{{url('change-password')}}">
                            @csrf
                            <input type="hidden" name="user_id" value="">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="old_pass">Old Password</label>
                                        <input type="password" class="form-control shadow-none" name="old_pass" id="old_pass" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="new_pass">New Password</label>
                                        <input type="password" class="form-control shadow-none" name="new_pass" id="new_pass" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="c_new_pass">Confirm New Password</label>
                                        <input type="password" class="form-control shadow-none" name="c_new_pass" id="c_new_pass" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div>
                                        <button class="btn btn-success px-4" style="font-family: med;" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4 mt-2" style="">
                    <h5 style="font-family: bold;" class="text-white m-0">Withdrawal Accounts:</h5>
                    <div class="mt-3">
                        <form method="post" name="withdrawal-account-form" id="withdrawal-account-form" action="{{url('withdrawal-account')}}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="bank">Bank / Payment Method</label>
                                        <input type="text" class="form-control shadow-none" name="bank" id="bank"
                                               placeholder="e.g">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="account_title">Account Title</label>
                                        <input type="text" class="form-control shadow-none" name="account_title" id="account_title"
                                               placeholder="John Doe">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="account_no">Account Number</label>
                                        <input type="text" class="form-control shadow-none" name="account_no" id="account_no"
                                               placeholder="19AL353737346536">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="mobile_no">Phone Number</label>
                                        <input type="text" class="form-control shadow-none" name="mobile_no" id="mobile_no"
                                               placeholder="xxxxxxxxxx">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div>
                                        <button class="btn btn-success px-4" style="font-family: med;" type="submit">Add Account</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('scripts')
    <script>
        $(document).ready(function () {
            $("#withdrawal-account-form").validate({
                rules:{
                    bank: {
                        required:true
                    },
                    account_title: {
                        required:true
                    },
                    account_no: {
                        required:true
                    },
                    mobile_no: {
                        required:true
                    }
                },
                messages:{
                    bank: {
                        required: "Please select Bank*"
                    },
                    account_title: {
                        required: "Please enter Account Name*"
                    },
                    account_no: {
                        required: "Please enter Account Number*"
                    },
                    mobile_no: {
                        required: "Please select Phone Number*"
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });
        });

        function setPhoto(ins) {
            const [file] = ins.files
            if (file) {
                var output = document.getElementById('profile-photo');
                output.src = URL.createObjectURL(file);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            }
        }
    </script>
@stop

@extends('site.trade.layout.index')

@section('page-title')
    My Account
@stop

@section('title')
    My Account
@stop

@section('content')
    <div class="card border-0">
        <div class="card-body bg-self shadow-sm p-0 px-2 py-3">
            <div class="row">
                <div class="col-md-4 mt-3" style="border-right: 1px dashed #6c757d;">
                    <h5 style="font-family: bold;" class="text-white m-0">Personal Info:</h5>
                    <div class="mt-3">
                        <form method="post" autocomplete="off" name="user-form" id="user-form" enctype="multipart/form-data" action="{{url('profile')}}">
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
                                        <label class="form-label required" for="phone_number">Phone Number</label>
                                        <input type="number" min="0" class="form-control shadow-none" name="phone_number" id="phone_number"
                                               placeholder="03xxxxxxxxx" value="{{auth()->user()->phone_number}}" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="email">Email</label>
                                        <input type="email" readonly class="form-control shadow-none" name="email" id="email"
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

                <div class="col-md-4 mt-3" style="border-right: 1px dashed #6c757d;">
                    <h5 style="font-family: bold;" class="text-white m-0">Security:</h5>
                    <div class="mt-3">
                        <form method="post" autocomplete="off" name="change-password-form" id="change-password-form" action="{{url('change-password')}}">
                            @csrf
                            <input type="hidden" name="id" value="{{auth()->id()}}">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="new_pass">New Password</label>
                                        <input type="password" class="form-control shadow-none" name="password" id="password">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label required" for="c_new_pass">Confirm New Password</label>
                                        <input type="password" class="form-control shadow-none" name="c_password" id="c_password">
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

                <div class="col-md-4 mt-3">
                    <h5 style="font-family: bold;" class="text-white m-0">Withdrawal Accounts:</h5>
                    <div class="mt-3">
                        <form method="post" autocomplete="off" name="withdrawal-account-form" id="withdrawal-account-form" action="{{url('withdrawal-account')}}">
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
                                        <input type="number" min="0" class="form-control shadow-none" name="mobile_no" id="mobile_no"
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

            $("#user-form").validate({
                rules:{
                    name: {
                        required:true
                    },
                    phone_number: {
                        required:true,
                        maxlength: 11,
                        minLength: 11
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });

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
                        required: "Please enter Bank*"
                    },
                    account_title: {
                        required: "Please enter Account Name*"
                    },
                    account_no: {
                        required: "Please enter Account Number*"
                    },
                    mobile_no: {
                        required: "Please enter Phone Number*"
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });

            $('#password').val('');

            $("#change-password-form").validate({
                rules:{
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 12
                    },
                    c_password: {
                        equalTo: "#password"
                    }
                },
                messages:{
                    password: {
                        required: "Password is Required*",
                        minlength: "Password must be minimum 8 characters long",
                        maxlength: "Password must be maximum 12 characters long"
                    },
                    c_password: {
                        equalTo: "Password must be equal to entered password"
                    },
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

@extends('site.layout.index')

@section('page-title')
    Reset Password
@stop

@section('content')
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{session()->get('error')}}
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{session()->get('success')}}
        </div>
    @endif
    <div class="col-md-4 m-auto my-5">
        <h1 class="signin-header">Reset Password</h1>
        <div class="singin-box rounded">
            <form method="post" action="{{route('do-reset-password')}}" name="reset-password-form" id="reset-password-form">
                @csrf
                <input type="hidden" name="token" id="token" value="{{ $user -> password_reset_token }}">
                <div class="mb-3">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control shadow-none"
                               name="password" id="password" placeholder="password">
                        <span class="input-group-text cursor-pointer bg-transparent" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password text-white"></i>
                        </span>
                    </div>
                    <label id="password-error" class="error" for="password"></label>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="c_password">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control shadow-none"
                               name="confirm_password" id="confirm_password" placeholder="confirm password">
                        <span class="input-group-text cursor-pointer bg-transparent" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password-2 text-white"></i>
                        </span>
                    </div>
                    <label id="c_password-error" class="error" for="confirm_password"></label>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="d-grid">
                    <button type="submit" class="btn btn-success py-3" style="font-family: med;">
                        Reset Password<i class="fas fa-check-circle ms-2"></i>
                    </button>
                </div>
            </form>
            <div class="mt-2 text-center">
                <span class="text-white">
                    Don't have an account?
                    <a href="{{url('/register')}}" style="text-decoration: none; font-family: med; font-size: 16px;">
                        Register Yourself
                    </a>
                </span>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $('.toggle-password').click(function() {
            if(document.getElementById("password").type == "password"){
                $('#password').get(0).type= 'text'
                $(this).removeClass('fa-eye-slash')
                $(this).addClass('fa-eye')
            }else{
                $('#password').get(0).type= 'password'
                $(this).removeClass('fa-eye')
                $(this).addClass('fa-eye-slash')
            }
        });


        $('.toggle-confirm-password').click(function() {
            if(document.getElementById("confirm_password").type == "password"){
                $('#confirm_password').get(0).type= 'text'
                $(this).removeClass('fa-eye-slash')
                $(this).addClass('fa-eye')
            }else{
                $('#confirm_password').get(0).type= 'password'
                $(this).removeClass('fa-eye')
                $(this).addClass('fa-eye-slash')
            }
        });

        $(document).ready(function() {
            $("#reset-password-form").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 12
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                        maxlength: 12,
                        equalTo: "#password",
                    },
                },
                messages: {
                    password: {
                        required: "Enter password",
                        minlength: "Password must contain at least 8 characters",
                    },
                    confirm_password: {
                        required: "Enter password",
                        minlength: "Password must contain at least 8 characters",
                        equalTo: "Both password should be same",
                    },
                },
                submitHandler:function(form) {
                    return true;
                }
            });
        });
    </script>
@stop

@extends('site.layout.index')

@section('page-title')
    Login
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
        <h1 class="signin-header m-0">Sign In To Your Account</h1>
        <div class="singin-box rounded">
            <form method="POST" action="{{url('login')}}" name="login-form" id="login-form">
                @csrf
                <div class="mb-4">
                    <label class="form-label" for="identifier">Email or Phone</label>
                    <input type="text" class="form-control shadow-none" name="identifier" id="identifier" required placeholder="Enter Email or Phone">
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="{{url('forgot-password')}}" style="text-decoration: none; font-size: 14px; font-family: med;">
                            Forgot Password?
                        </a>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control shadow-none"
                               name="password" id="password" placeholder="Password">
                        <span class="input-group-text bg-transparent cursor-pointer" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password text-white"></i>
                        </span>
                    </div>
                    <label id="password-error" class="error" for="password"></label>
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
                        Log in<i class="fas fa-arrow-circle-right ms-2"></i>
                    </button>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url('authorized/google') }}">
                        <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin-left: 3em;">
                    </a>
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
    <script type="text/javascript">
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

        $(document).ready(function (){
            $("#login-form").validate({
                rules:{
                    identifier: {
                        required:true
                    },
                    password: {
                        required: true
                    }
                },
                messages:{
                    identifier: {
                        required:"This is Required*",
                    },
                    password: {
                        required: "Password is Required*",
                    }
                },
                submitHandler:function(form){
                    return true;
                }
            });
        });
    </script>
@stop

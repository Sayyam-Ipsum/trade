@extends('site.layout.index')

@section('page-title')
    Register
@stop

@section('content')
    <div class="col-md-4 m-auto my-5">
        <h1 class="signin-header">Sign Up</h1>
        <div class="singin-box rounded">
            <form method="POST" action="{{url('register')}}" name="register-form" id="register-form">
                @csrf
                @if(@$refCode)
                    <input type="hidden" name="refCode" value="{{@$refCode}}">
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" class="form-control shadow-none" placeholder="name" name="name" id="name" required="">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control shadow-none" name="email" placeholder="email" id="email" required="">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="phone_number">Phone Number</label>
                    <input type="number" min="0" maxlength="11" class="form-control shadow-none" name="phone_number" placeholder="phone_number" id="phone_number" required="">
                </div>
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
                               name="c_password" id="c_password" placeholder="confirm password">
                        <span class="input-group-text cursor-pointer bg-transparent" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password-2 text-white"></i>
                        </span>
                    </div>
                    <label id="c_password-error" class="error" for="c_password"></label>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success py-3" style="font-family: med;">
                        Registration<i class="fas fa-arrow-circle-right ms-2"></i>
                    </button>
                </div>
            </form>
            <div class="mt-2 text-center">
                <span class="text-white">
                    Already have an account?
                    <a href="{{url('/login')}}" style="text-decoration: none; font-family: med; font-size: 16px;">
                        Sign In
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

        $('.toggle-password-2').click(function() {
            if(document.getElementById("c_password").type == "password"){
                $('#c_password').get(0).type= 'text'
                $(this).removeClass('fa-eye-slash')
                $(this).addClass('fa-eye')
            }else{
                $('#c_password').get(0).type= 'password'
                $(this).removeClass('fa-eye')
                $(this).addClass('fa-eye-slash')
            }
        });

        $(document).ready(function (){
            $("#register-form").validate({
                rules:{
                    name: {
                        required:true
                    },
                    gender: {
                        required:true
                    },
                    phone_number: {
                        required:true,
                        minlength:11,
                        maxlength:11
                    },
                    email: {
                        required: true,
                        email: true
                    },
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
                    name: {
                        required:"Name is Required*"
                    },
                    email: {
                        required: "Email is Required*",
                        email: "Please enter Valid Email*"
                    },
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
    </script>
@stop

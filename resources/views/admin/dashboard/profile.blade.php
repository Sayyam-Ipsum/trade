@extends('admin.templates.index')

@section('page-title')
    Profile
@stop

@section('title')
    Profile
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="post" name="setting-form" id="setting-form" enctype="multipart/form-data" action="{{url('admin/profile')}}">
                @csrf
                <input type="hidden" name="id" value="{{auth()->id()}}">
                <div class="d-flex justify-content-end">
                    <div class="col-md-4 col-sm-4 text-center mt-1">
                        @php
                            $url = auth()->user()->photo ? auth()->user()->photo : asset('assets/site/img/user.png')
                        @endphp
                        <img src="{{$url}}" width="130px" height="130px" id="profile-photo"
                             style="object-fit: cover;object-position: top;border-radius: 50%;"
                             class="border border-muted p-1 shadow">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required" for="name">Name</label>
                            <input type="text" class="form-control" value="{{auth()->user()->name}}"
                                   name="name" id="name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required" for="email">Email</label>
                            <input type="email" class="form-control" value="{{auth()->user()->email}}"
                                   name="email" id="email">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="photo">Profile Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" onchange="setPhoto(this)">
                        </div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>


            <form method="post" autocomplete="off" name="password-change-form" class="mt-3" id="password-change-form" action="{{url('admin/change-password')}}">
                @csrf
                <input type="hidden" name="id" value="{{auth()->id()}}">
                <div class="row">
                    <div class="col-md-4">
                        <label>Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control shadow-none"
                                   name="password" id="password" placeholder="password">
                            <span class="input-group-text cursor-pointer bg-transparent" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password text-dark"></i>
                        </span>
                        </div>
                        <label id="password-error" class="error" for="password"></label>
                    </div>
                    <div class="col-md-4">
                        <label>Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control shadow-none"
                                   name="c_password" id="c_password" placeholder="confirm password">
                            <span class="input-group-text cursor-pointer bg-transparent" style="border: 1px solid gray;">
                            <i class="fa eye fa-eye-slash toggle-password-2 text-dark"></i>
                        </span>
                        </div>
                        <label id="c_password-error" class="error" for="c_password"></label>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary mt-1" type="submit">Change Password</button>
                </div>
            </form>


        </div>
    </div>
@stop

@section('scripts')
    <script>

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

        $('#password').val('');

        $("#password-change-form").validate({
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
        </script>
@stop

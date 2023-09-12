@extends('template.email')
@section('email.content')
    <p>Welcome {{$name}},</p>
    <p>Now you are part of <strong>{{$tenant}}</strong> in Office Space Sharing System</p>
    <p>You can now log in to {{url('admin-login')}} </p>
    <p>your login credentials are <strong>email</strong> : {{$email}} and <strong>password</strong> : {{$password}}</p>

    <p>Team Office Space Sharing System</p>


@stop


@extends('mails.template.email')
@section('email.content')

<p>Hello {{ $user->name}},</p>
<p>We received a request to reset your Easy Trade password</p>
<p>Click the link below to set a new password:</p>
<p><a href="{{ url('/reset-password/'.$user->password_reset_token) }}">Reset Password</a></p>

<p>If you don’t want to reset your password, you can ignore this email. If you have any issues logging in with your new password, you can contact us 24/7 at support@easytrade.com.</p>

<p>Team Easy Trade</p>

@endsection('email.content')

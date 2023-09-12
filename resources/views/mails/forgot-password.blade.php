<!DOCTYPE html>

<html>

<head>

    <title>Easy Trade</title>
    <style>
        .link{
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            color: white !important;
            background-color: black;
            margin-top: 15px;
        }
    </style>

</head>

<body>

<h1>{{ $details['title'] }}</h1>

<p>{{ $details['body'] }}</p>

<div>
    <a class="link" class="text-white" href="{{ route('change.password.get', $details['token']) }}">
        Reset Password Link
    </a>
</div>



<p>Thank you</p>

</body>

</html>

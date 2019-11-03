<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <h3>{{__('user.email.salutation')}}, {{$name}}.</h3>
    <h2><a href="{{env('RESET_PWD_FRONT_URL') . $token}}">{{__('user.email.click_here')}}</a> {{__('user.email.to_reset_pwd')}}.</h2>
</body>

</html>

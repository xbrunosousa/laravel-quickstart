<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <h3>{{__('signup.email.salutation')}}, {{$name}}.</h3>
    <h2><a href="{{env('VERIFICATION_FRONT_URL') . $hash}}">{{__('signup.email.click_here')}}</a> {{__('signup.email.to_activate_account')}}.</h2>
</body>

</html>

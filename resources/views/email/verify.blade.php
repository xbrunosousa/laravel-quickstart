<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <h3>Hello, {{$name}}.</h3>
    <h2><a href="{{env('VERIFICATION_FRONT_URL') . $hash}}">Click here</a> to activate your accont</h2>
</body>

</html>

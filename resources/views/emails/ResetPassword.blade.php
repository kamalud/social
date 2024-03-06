<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email reset</title>
</head>
<body>
     <div>
        <h1>{{$data['heading']}}</h1>
        <strong>Dear {{$data['name']}}</strong>
        <p>Here id the Verification code Reset your password {{$data['code']}}</p><br>
        Regrades {{env('APP_NAME')}}
     </div>
</body>
</html>
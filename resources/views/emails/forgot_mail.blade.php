<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <style>
        .container{
            text-align: center;
        }
        i{
            font-size: small;
            /* color: #f5f5f5; */
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ $message->embed(public_path().'/assets/images/company-logo2.svg') }}">
        <p><b>Dear</b> {{ $emailData['name'] }}! Your otp for reseting the password is here: <b>{{ $emailData['otp'] }}</b>.</p>
        <br>
        <p>Please use this otp to reset your password. <b>Thank You!</b></p>
        <i>Team <a href="https://scubadiving.thewebconcept.tech/">Dive Monies</a></i>
    </div>
</body>

</html>
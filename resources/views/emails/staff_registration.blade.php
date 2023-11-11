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
        <p><b>Hello dear!</b> You are getting this email because you are just registered to the <b>Dive Monies</b>. You can login to your account through giving address {{ $emailData['email'] }} and the password is <b>{{ $emailData['password'] }}</b>.</p>
        <br>
        <p><b>Thank You!</b></p>
        <i>Team <a href="https://scubadiving.thewebconcept.tech/">Dive Monies</a></i>
    </div>
</body>

</html>
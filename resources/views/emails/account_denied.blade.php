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
        <p><b>Dear</b> {{ $emailData['name'] }}! Your access to our services is denied because of some issue in your provided credentials.</p>
        <br>
        <p>We cannot provide you our service at this moment. <b>Thank You!</b></p>
        <i>Team <a href="https://scubadiving.thewebconcept.tech/">Dive Monies</a></i>
    </div>
</body>

</html>
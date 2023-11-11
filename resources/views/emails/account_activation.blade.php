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
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ $message->embed(public_path().'/assets/images/company-logo2.svg') }}">
        <p><b>Congratulations!</b> {{ $emailData['name'] }}, You have been approved by the <b>Dive Monies</b>. Now, you can login to our application.</p>
        <br>
        <p>Your account email is: {{ $emailData['email'] }} and password is: {{ $emailData['password'] }}</p>
        <i>Team <a href="https://scubadiving.thewebconcept.tech/">Dive Monies</a></i>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Created</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your account has been successfully created with the email {{ $user->email }}.</p>
    <p>Thank you for registering!</p>
</body>
</html>

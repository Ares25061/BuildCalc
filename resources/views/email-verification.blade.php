<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Подтверждение Email</title>
</head>
<body>
<h2>Подтвердите ваш Email</h2>

<p>Здравствуйте, {{ $user->name }}!</p>

<p>Пожалуйста, подтвердите ваш email адрес, нажав на кнопку ниже:</p>

<a href="{{ url('email/verify/'.$user->getKey().'/'.sha1($user->getEmailForVerification())) }}"
   style="background-color: #f97316; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
    Подтвердить Email
</a>

<p>Если вы не создавали аккаунт, просто проигнорируйте это письмо.</p>

<p>С уважением,<br>Ваше приложение</p>
</body>
</html>

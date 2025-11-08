<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Сброс пароля</title>
</head>
<body>
<h2>Здравствуйте!</h2>
<p>Вы получили это письмо потому что был запрошен сброс пароля для вашего аккаунта.</p>

<a href="{{ url('reset-password/'.$token).'?email='.urlencode($email) }}"
   style="background-color: #f97316; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
    Сбросить пароль
</a>

<p>Ссылка для сброса пароля будет действительна в течение 60 минут.</p>

<p>Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо.</p>

<p>С уважением,<br>Ваш MaterialHub</p>
</body>
</html>

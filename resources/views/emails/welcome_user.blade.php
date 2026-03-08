<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue</title>
</head>
<body>
    <h2>Bonjour {{ $user->name }},</h2>

    <p>Bienvenue sur {{ config('app.name') }} !</p>

    <p>Voici vos identifiants pour vous connecter :</p>
    <ul>
        <li><strong>Email :</strong> {{ $user->email }}</li>
        <li><strong>Mot de passe :</strong> {{ $passwordPlain }}</li>
    </ul>

    <p>Merci et bonne navigation !</p>
</body>
</html>

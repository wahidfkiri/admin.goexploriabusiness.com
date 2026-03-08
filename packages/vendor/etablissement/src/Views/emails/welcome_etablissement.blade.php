<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<h2>Bienvenue {{ $user->name }}</h2>

<p>Votre établissement <strong>{{ $etablissement->name }}</strong> a été créé avec succès.</p>

<p>Voici vos identifiants :</p>

<p>
Email : <strong>{{ $user->email }}</strong><br>
Mot de passe : <strong>{{ $password }}</strong>
</p>

<p>Vous pouvez vous connecter ici :</p>

<p>
<a href="{{ url('/login') }}">
Se connecter
</a>
</p>

<p>Merci.</p>

</body>
</html>
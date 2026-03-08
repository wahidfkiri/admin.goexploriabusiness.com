<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .credentials {
            background-color: white;
            border: 2px solid #4F46E5;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .info-box {
            background-color: #e8f4fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bienvenue sur {{ $data['site_name'] }} !</h1>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $data['name'] }}</strong>,</p>
        
        <p>Votre établissement <strong>{{ $data['etablissement_name'] }}</strong> a été créé avec succès sur notre plateforme.</p>
        
        <div class="info-box">
            <p><strong>Important :</strong> Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe dès votre première connexion.</p>
        </div>
        
        <div class="credentials">
            <h3>Vos identifiants de connexion :</h3>
            <p><strong>Email :</strong> {{ $data['email'] }}</p>
            <p><strong>Mot de passe :</strong> {{ $data['password'] }}</p>
        </div>
        
        <p>Vous pouvez vous connecter dès maintenant en cliquant sur le bouton ci-dessous :</p>
        
        <a href="{{ $data['login_url'] }}" class="button">Se connecter maintenant</a>
        
        <p>Une fois connecté, vous pourrez :</p>
        <ul>
            <li>Accéder à votre tableau de bord</li>
            <li>Modifier les informations de votre établissement</li>
            <li>Gérer vos activités</li>
            <li>Consulter vos statistiques</li>
        </ul>
        
        <p>Si vous avez des questions ou besoin d'assistance, n'hésitez pas à nous contacter à :</p>
        <p><a href="mailto:{{ $data['support_email'] }}">{{ $data['support_email'] }}</a></p>
        
        <p>Cordialement,<br>L'équipe {{ $data['site_name'] }}</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        <p>&copy; {{ date('Y') }} {{ $data['site_name'] }}. Tous droits réservés.</p>
    </div>
</body>
</html>
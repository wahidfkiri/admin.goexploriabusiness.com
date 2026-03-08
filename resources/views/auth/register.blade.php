<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme Moderne</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3B82F6;
            --primary-hover: #2563EB;
            --secondary: #10B981;
            --accent: #8B5CF6;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        
        /* Cercles animés en fond */
        .bg-circles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(20px);
            animation: float 20s infinite linear;
        }
        
        .circle:nth-child(1) {
            width: 150px;
            height: 150px;
            background: linear-gradient(45deg, #3B82F6, #8B5CF6);
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .circle:nth-child(2) {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #10B981, #3B82F6);
            top: 60%;
            right: 10%;
            animation-delay: -5s;
        }
        
        .circle:nth-child(3) {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #8B5CF6, #EC4899);
            bottom: 10%;
            left: 20%;
            animation-delay: -10s;
        }
        
        .circle:nth-child(4) {
            width: 180px;
            height: 180px;
            background: linear-gradient(45deg, #F59E0B, #10B981);
            top: 20%;
            right: 20%;
            animation-delay: -7s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) rotate(90deg);
            }
            50% {
                transform: translateY(20px) rotate(180deg);
            }
            75% {
                transform: translateY(-10px) rotate(270deg);
            }
        }
        
        .circle:nth-child(5) {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #EC4899, #F59E0B);
            bottom: 30%;
            right: 5%;
            animation-delay: -12s;
        }
        
        .circle:nth-child(6) {
            width: 90px;
            height: 90px;
            background: linear-gradient(45deg, #06B6D4, #3B82F6);
            top: 70%;
            left: 5%;
            animation-delay: -3s;
            animation-duration: 25s;
        }
        
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .card::-webkit-scrollbar {
            width: 4px;
        }
        
        .card::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .card::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--accent));
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .input-field {
            border: 1.5px solid #E5E7EB;
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        .password-toggle {
            cursor: pointer;
            color: #6B7280;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .floating-label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            transition: all 0.3s ease;
            pointer-events: none;
            background: white;
            padding: 0 5px;
        }
        
        .input-field:focus + .floating-label,
        .input-field:not(:placeholder-shown) + .floating-label {
            top: 0;
            font-size: 11px;
            color: var(--primary);
            transform: translateY(-50%);
        }
        
        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        /* Alert styles */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out;
        }
        
        .alert-success {
            background-color: #D1FAE5;
            border: 1px solid #10B981;
            color: #065F46;
        }
        
        .alert-error {
            background-color: #FEE2E2;
            border: 1px solid #EF4444;
            color: #991B1B;
        }
        
        .alert i {
            margin-right: 10px;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Checkbox styles */
        .checkbox-custom {
            width: 18px;
            height: 18px;
            border: 2px solid #D1D5DB;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .checkbox-custom.checked {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .checkbox-custom.checked i {
            color: white;
            font-size: 11px;
        }
        
        /* Step indicator */
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        
        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #E5E7EB;
            color: #6B7280;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .step.active .step-number {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .step-label {
            margin-top: 8px;
            font-size: 11px;
            color: #6B7280;
            text-align: center;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--primary);
            font-weight: 600;
        }
        
        .step-line {
            width: 80px;
            height: 2px;
            background: #E5E7EB;
            margin: 0 8px;
            position: relative;
            top: 16px;
        }
        
        .step-line.active {
            background: var(--primary);
        }
        
        /* Hidden form steps */
        .form-step {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }
        
        .form-step.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .max-w-md {
         max-width: 50rem !important;
        }
    </style>
</head>
<body>
    <!-- Cercles colorés animés en fond -->
    <div class="bg-circles">
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
        <div class="circle"></div>
    </div>
    
    <div class="w-full max-w-md px-4">
        <div class="card p-6">
            <!-- Logo et en-tête -->
            <div class="text-center mb-6">
                <div class="w-30 h-30 flex items-center justify-center mx-auto mb-4">
                    <img src="{{asset('logo.png')}}" style="width:200px;">
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Créer votre compte</h2>
                <p class="text-gray-600 text-sm mt-1">Rejoignez notre plateforme en quelques étapes</p>
            </div>

            <!-- Indicateur d'étapes -->
            <div class="step-indicator">
                <div class="step active" id="step1">
                    <div class="step-number">1</div>
                    <div class="step-label">Compte</div>
                </div>
                <div class="step-line" id="line1"></div>
                <div class="step" id="step2">
                    <div class="step-number">2</div>
                    <div class="step-label">Profil</div>
                </div>
                <div class="step-line" id="line2"></div>
                <div class="step" id="step3">
                    <div class="step-number">3</div>
                    <div class="step-label">Finalisation</div>
                </div>
            </div>

            <!-- Alert div pour succès/erreurs -->
            <div id="alertContainer" class="hidden"></div>

            <!-- Étape 1 : Informations du compte -->
            <form id="registerForm" class="space-y-5">
                @csrf
                
                <!-- Étape 1 -->
                <div id="step1Form" class="form-step active">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations du compte</h3>
                    
                    <!-- Nom -->
                    <div class="relative">
                        <input type="text" 
                               id="name"
                               name="name"
                               class="input-field w-full"
                               placeholder=" "
                               required>
                        <label class="floating-label">Nom complet</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-user text-gray-400 text-sm"></i>
                        </div>
                        <div class="text-red-500 text-xs mt-1 hidden" id="nameError"></div>
                    </div>

                    <!-- Email -->
                    <div class="relative">
                        <input type="email" 
                               id="email"
                               name="email"
                               class="input-field w-full"
                               placeholder=" "
                               required>
                        <label class="floating-label">Adresse email</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <div class="text-red-500 text-xs mt-1 hidden" id="emailError"></div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="relative">
                        <input type="password" 
                               id="password"
                               name="password"
                               class="input-field w-full pr-12"
                               placeholder=" "
                               required>
                        <label class="floating-label">Mot de passe</label>
                        <button type="button" 
                                onclick="togglePassword('password', 'passwordEyeIcon')" 
                                class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-eye text-sm" id="passwordEyeIcon"></i>
                        </button>
                        <div class="text-red-500 text-xs mt-1 hidden" id="passwordError"></div>
                        <p class="text-gray-500 text-xs mt-2">Minimum 8 caractères avec majuscule, minuscule et chiffre</p>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="relative">
                        <input type="password" 
                               id="password_confirmation"
                               name="password_confirmation"
                               class="input-field w-full pr-12"
                               placeholder=" "
                               required>
                        <label class="floating-label">Confirmer le mot de passe</label>
                        <button type="button" 
                                onclick="togglePassword('password_confirmation', 'passwordConfirmEyeIcon')" 
                                class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-eye text-sm" id="passwordConfirmEyeIcon"></i>
                        </button>
                        <div class="text-red-500 text-xs mt-1 hidden" id="passwordConfirmationError"></div>
                    </div>

                    <!-- Bouton Suivant -->
                    <button type="button" onclick="nextStep()" class="btn-primary w-full mt-2">
                        Suivant
                    </button>
                </div>

                <!-- Étape 2 : Informations de l'établissement -->
                <div id="step2Form" class="form-step">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations professionnelles</h3>
                    
                    <!-- Nom de l'établissement -->
                    <div class="relative">
                        <input type="text" 
                               id="etablissement_name"
                               name="etablissement_name"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Nom de l'établissement</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-building text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Prénom du responsable -->
                    <div class="relative">
                        <input type="text" 
                               id="lname"
                               name="lname"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Prénom du responsable</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-user-tie text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="relative">
                        <input type="tel" 
                               id="phone"
                               name="phone"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Téléphone</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-phone text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="relative">
                        <input type="text" 
                               id="adresse"
                               name="adresse"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Adresse</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Ville -->
                    <div class="relative">
                        <input type="text" 
                               id="ville"
                               name="ville"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Ville</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-city text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Code postal -->
                    <div class="relative">
                        <input type="text" 
                               id="zip_code"
                               name="zip_code"
                               class="input-field w-full"
                               placeholder=" ">
                        <label class="floating-label">Code postal</label>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-mail-bulk text-gray-400 text-sm"></i>
                        </div>
                    </div>

                    <!-- Boutons navigation -->
                    <div class="flex space-x-3">
                        <button type="button" onclick="prevStep()" class="btn-primary w-1/2 bg-gray-500 hover:bg-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i> Précédent
                        </button>
                        <button type="button" onclick="nextStep()" class="btn-primary w-1/2">
                            Suivant
                        </button>
                    </div>
                </div>

                <!-- Étape 3 : Conditions et finalisation -->
                <div id="step3Form" class="form-step">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Finalisation</h3>
                    
                    <!-- Conditions d'utilisation -->
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Conditions d'utilisation</h4>
                        <div class="text-gray-600 text-sm max-h-40 overflow-y-auto pr-2">
                            <p class="mb-2">En créant un compte, vous acceptez nos conditions d'utilisation :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Respect des données personnelles et confidentialité</li>
                                <li>Utilisation professionnelle de la plateforme</li>
                                <li>Exactitude des informations fournies</li>
                                <li>Respect des droits d'auteur et propriété intellectuelle</li>
                                <li>Non-utilisation à des fins illégales ou frauduleuses</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Acceptation des conditions -->
                    <div class="flex items-center space-x-2 cursor-pointer mb-4" onclick="toggleTerms()">
                        <div class="checkbox-custom" id="termsCheckbox">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-gray-700 text-sm">
                            J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:underline">politique de confidentialité</a>
                        </span>
                    </div>
                    <div class="text-red-500 text-xs mt-1 hidden" id="termsError"></div>

                    <!-- Newsletter -->
                    <div class="flex items-center space-x-2 cursor-pointer mb-6" onclick="toggleNewsletter()">
                        <div class="checkbox-custom" id="newsletterCheckbox">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="text-gray-700 text-sm">
                            Je souhaite recevoir des offres spéciales et des mises à jour (optionnel)
                        </span>
                    </div>

                    <!-- Boutons navigation et soumission -->
                    <div class="flex space-x-3">
                        <button type="button" onclick="prevStep()" class="btn-primary w-1/2 bg-gray-500 hover:bg-gray-600">
                            <i class="fas fa-arrow-left mr-2"></i> Précédent
                        </button>
                        <button type="submit" id="registerBtn" class="btn-primary w-1/2 flex items-center justify-center">
                            <span id="registerBtnText">S'inscrire</span>
                            <div id="registerSpinner" class="hidden ml-2">
                                <div class="spinner"></div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Lien vers connexion -->
            <div class="text-center mt-5">
                <p class="text-gray-600 text-sm">
                    Déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                        Se connecter
                    </a>
                </p>
            </div>

            <!-- Footer -->
            <div class="text-center pt-4 border-t border-gray-200">
                <p class="text-gray-600 text-xs">
                    En vous inscrivant, vous acceptez notre
                    <a href="#" class="text-blue-600 hover:underline font-medium">Politique de confidentialité</a>
                </p>
                <p class="text-gray-500 text-xs mt-3">
                    © 2026 Go Exploria Business. Tous droits réservés.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentStep = 1;
        const totalSteps = 3;

        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Toggle terms checkbox
        function toggleTerms() {
            const checkbox = document.getElementById('termsCheckbox');
            checkbox.classList.toggle('checked');
        }

        // Toggle newsletter checkbox
        function toggleNewsletter() {
            const checkbox = document.getElementById('newsletterCheckbox');
            checkbox.classList.toggle('checked');
        }

        // Show alert function
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alertContainer');
            
            let icon = 'fa-check-circle';
            let alertClass = 'alert-success';
            
            if (type === 'error') {
                icon = 'fa-exclamation-circle';
                alertClass = 'alert-error';
            }
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass}">
                    <i class="fas ${icon}"></i>
                    ${message}
                </div>
            `;
            
            alertContainer.classList.remove('hidden');
            
            // Auto-hide success alerts after 5 seconds
            if (type === 'success') {
                setTimeout(() => {
                    alertContainer.classList.add('hidden');
                }, 5000);
            }
        }

        // Validate step 1
        function validateStep1() {
            let isValid = true;
            
            // Reset errors
            document.getElementById('nameError').classList.add('hidden');
            document.getElementById('emailError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            document.getElementById('passwordConfirmationError').classList.add('hidden');
            
            // Validate name
            const name = document.getElementById('name').value.trim();
            if (!name) {
                document.getElementById('nameError').textContent = 'Le nom est requis';
                document.getElementById('nameError').classList.remove('hidden');
                document.getElementById('name').classList.add('shake');
                isValid = false;
            }
            
            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                document.getElementById('emailError').textContent = 'L\'email est requis';
                document.getElementById('emailError').classList.remove('hidden');
                document.getElementById('email').classList.add('shake');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById('emailError').textContent = 'Format d\'email invalide';
                document.getElementById('emailError').classList.remove('hidden');
                document.getElementById('email').classList.add('shake');
                isValid = false;
            }
            
            // Validate password
            const password = document.getElementById('password').value;
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
            if (!password) {
                document.getElementById('passwordError').textContent = 'Le mot de passe est requis';
                document.getElementById('passwordError').classList.remove('hidden');
                document.getElementById('password').classList.add('shake');
                isValid = false;
            } else if (!passwordRegex.test(password)) {
                document.getElementById('passwordError').textContent = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un chiffre';
                document.getElementById('passwordError').classList.remove('hidden');
                document.getElementById('password').classList.add('shake');
                isValid = false;
            }
            
            // Validate password confirmation
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            if (password !== passwordConfirmation) {
                document.getElementById('passwordConfirmationError').textContent = 'Les mots de passe ne correspondent pas';
                document.getElementById('passwordConfirmationError').classList.remove('hidden');
                document.getElementById('password_confirmation').classList.add('shake');
                isValid = false;
            }
            
            // Remove shake animation
            setTimeout(() => {
                document.getElementById('name').classList.remove('shake');
                document.getElementById('email').classList.remove('shake');
                document.getElementById('password').classList.remove('shake');
                document.getElementById('password_confirmation').classList.remove('shake');
            }, 500);
            
            return isValid;
        }

        // Validate step 3 (terms)
        function validateStep3() {
            const termsChecked = document.getElementById('termsCheckbox').classList.contains('checked');
            const termsError = document.getElementById('termsError');
            
            if (!termsChecked) {
                termsError.textContent = 'Vous devez accepter les conditions d\'utilisation';
                termsError.classList.remove('hidden');
                return false;
            }
            
            termsError.classList.add('hidden');
            return true;
        }

        // Navigate to next step
        function nextStep() {
            if (currentStep === 1) {
                if (!validateStep1()) {
                    return;
                }
            } else if (currentStep === 2) {
                // Step 2 doesn't require validation
            } else if (currentStep === 3) {
                return; // Step 3 is for submission
            }
            
            // Hide current step
            document.getElementById(`step${currentStep}Form`).classList.remove('active');
            document.getElementById(`step${currentStep}`).classList.remove('active');
            
            // Activate line between steps
            if (currentStep < totalSteps) {
                document.getElementById(`line${currentStep}`).classList.add('active');
            }
            
            // Move to next step
            currentStep++;
            
            // Show next step
            document.getElementById(`step${currentStep}Form`).classList.add('active');
            document.getElementById(`step${currentStep}`).classList.add('active');
        }

        // Navigate to previous step
        function prevStep() {
            // Hide current step
            document.getElementById(`step${currentStep}Form`).classList.remove('active');
            document.getElementById(`step${currentStep}`).classList.remove('active');
            
            // Deactivate line between steps
            if (currentStep > 1) {
                document.getElementById(`line${currentStep - 1}`).classList.remove('active');
            }
            
            // Move to previous step
            currentStep--;
            
            // Show previous step
            document.getElementById(`step${currentStep}Form`).classList.add('active');
            document.getElementById(`step${currentStep}`).classList.add('active');
        }

        // Form submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Validate terms
            if (!validateStep3()) {
                return;
            }
            
            // Reset errors and hide alert
            document.getElementById('termsError').classList.add('hidden');
            document.getElementById('alertContainer').classList.add('hidden');
            
            // Get elements
            const btn = document.getElementById('registerBtn');
            const btnText = document.getElementById('registerBtnText');
            const spinner = document.getElementById('registerSpinner');
            
            // Show loading
            btn.disabled = true;
            btnText.textContent = 'Inscription en cours...';
            spinner.classList.remove('hidden');
            
            // Prepare form data
            const formData = new FormData();
            
            // User data
            formData.append('name', document.getElementById('name').value.trim());
            formData.append('email', document.getElementById('email').value.trim());
            formData.append('password', document.getElementById('password').value);
            formData.append('password_confirmation', document.getElementById('password_confirmation').value);
            
            // Etablissement data
            formData.append('etablissement_name', document.getElementById('etablissement_name').value.trim());
            formData.append('lname', document.getElementById('lname').value.trim());
            formData.append('phone', document.getElementById('phone').value.trim());
            formData.append('adresse', document.getElementById('adresse').value.trim());
            formData.append('ville', document.getElementById('ville').value.trim());
            formData.append('zip_code', document.getElementById('zip_code').value.trim());
            
            // Terms and newsletter
            formData.append('terms', document.getElementById('termsCheckbox').classList.contains('checked') ? '1' : '0');
            formData.append('newsletter', document.getElementById('newsletterCheckbox').classList.contains('checked') ? '1' : '0');
            
            try {
                const response = await fetch('{{ route("ajax.register") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Success - show alert
                    showAlert(data.message || 'Inscription réussie ! Redirection en cours...', 'success');
                    
                    // Animation de succès
                    btn.classList.add('bg-green-500');
                    btn.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-blue-600');
                    
                    // Redirection
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("login") }}';
                    }, 1500);
                    
                } else {
                    // Error handling
                    if (data.errors) {
                        // Validation errors
                        for (const field in data.errors) {
                            const errorElement = document.getElementById(field + 'Error');
                            const inputElement = document.getElementById(field);
                            
                            if (errorElement && inputElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.remove('hidden');
                                inputElement.classList.add('shake');
                                
                                setTimeout(() => {
                                    inputElement.classList.remove('shake');
                                }, 500);
                            }
                        }
                        
                        // Scroll to first error
                        const firstError = document.querySelector('[id$="Error"]:not(.hidden)');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    } else {
                        // General error
                        showAlert(data.message || 'Erreur lors de l\'inscription', 'error');
                    }
                }
                
            } catch (error) {
                console.error('Error:', error);
                showAlert('Erreur réseau. Veuillez réessayer.', 'error');
            } finally {
                // Reset button
                btn.disabled = false;
                btnText.textContent = 'S\'inscrire';
                spinner.classList.add('hidden');
            }
        });
    </script>
</body>
</html>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
        h2 {
            font-weight: 600;
            font-size: 1.25rem !important;
        }
        /* Animation pour le bouton chatbot */
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(74, 108, 247, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(74, 108, 247, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(74, 108, 247, 0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .chatbot-toggle {
            animation: pulse 2s infinite;
        }
        
        .chatbot-popup {
            animation: slideIn 0.3s ease-out;
        }
        
        .message {
            animation: fadeIn 0.3s ease;
        }
        
        .typing-dots span {
            animation: bounce 1.4s infinite ease-in-out both;
        }
        
        .typing-dots span:nth-child(1) {
            animation-delay: -0.32s;
        }
        
        .typing-dots span:nth-child(2) {
            animation-delay: -0.16s;
        }
        
        @keyframes bounce {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }
        
        /* Scrollbar personnalisée */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-messages::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Style pour les messages */
        .user-message {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .bot-message {
            background: #f1f5f9;
            color: #1e293b;
            border-bottom-left-radius: 5px;
        }
        
        /* Suggestions */
        .suggestion-chip {
            transition: all 0.3s ease;
        }
        
        .suggestion-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .chatbot-popup {
                width: 95vw !important;
                right: 2.5vw !important;
                bottom: 100px !important;
                height: 70vh !important;
            }
            
            .chatbot-toggle {
                bottom: 20px !important;
                right: 20px !important;
                width: 60px !important;
                height: 60px !important;
                font-size: 22px !important;
            }
        }
        #chatbotPopup {
            height:auto;
            width: 500px;
        }
    </style>
    <!-- Bouton du chatbot en cercle fixe -->
    <button id="chatbotToggle" class="chatbot-toggle fixed bottom-8 right-8 z-50 w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full shadow-2xl border-4 border-white flex items-center justify-center cursor-pointer transition-all duration-300 hover:scale-110 hover:shadow-3xl">
        <i class="fas fa-comments text-2xl"></i>
        <!-- <span class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-ping">!</span> -->
    </button>

    <!-- Popup du chatbot (caché par défaut) -->
    <div id="chatbotPopup" class="chatbot-popup fixed bottom-32 right-8 z-40 w-96 h-[600px] bg-white rounded-2xl shadow-2xl overflow-hidden hidden flex flex-col border border-gray-200" style="z-index:9999">
        <!-- En-tête du chatbot -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    
                    <div>
                        <h2 class="text-xl font-bold" style="font-size:30px;">Assistant Virtuel</h2>
                        <p class="text-blue-100 text-sm">Je suis là pour vous aider !</p>
                    </div>
                </div>
                <button id="closeChatbot" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl" style="font-size:30px;"></i>
                </button>
            </div>
            <div class="mt-4 flex space-x-2 overflow-x-auto pb-2">
                <button class="suggestion-chip px-3 py-2 bg-white/20 rounded-full text-sm whitespace-nowrap hover:bg-white/30 transition">
                    <i class="fas fa-utensils mr-2"></i>Restaurants
                </button>
                <button class="suggestion-chip px-3 py-2 bg-white/20 rounded-full text-sm whitespace-nowrap hover:bg-white/30 transition">
                    <i class="fas fa-hotel mr-2"></i>Hôtels
                </button>
                <button class="suggestion-chip px-3 py-2 bg-white/20 rounded-full text-sm whitespace-nowrap hover:bg-white/30 transition">
                    <i class="fas fa-map-marker-alt mr-2"></i>Itinéraires
                </button>
                <button class="suggestion-chip px-3 py-2 bg-white/20 rounded-full text-sm whitespace-nowrap hover:bg-white/30 transition">
                    <i class="fas fa-info-circle mr-2"></i>Aide
                </button>
            </div>
        </div>

        <!-- Messages du chat -->
        <div id="chatMessages" class="chat-messages flex-1 overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-white to-blue-50">
            <!-- Message de bienvenue -->
            <div class="message bot-message max-w-[80%] p-4 rounded-2xl shadow-sm">
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <span class="font-semibold text-gray-700">Assistant Virtuel</span>
                    <span class="ml-2 text-xs text-gray-500">Maintenant</span>
                </div>
                <p class="text-gray-800">Bonjour ! 👋 Je suis votre assistant virtuel. Je peux vous aider à découvrir des lieux intéressants, trouver des restaurants, des hôtels, ou calculer des itinéraires. Comment puis-je vous aider aujourd'hui ?</p>
            </div>
        </div>

        <!-- Indicateur de frappe -->
        <div id="typingIndicator" class="hidden p-4">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="typing-dots flex space-x-1">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                </div>
            </div>
        </div>

        <!-- Zone de saisie -->
        <div class="border-t border-gray-200 p-4 bg-white">
            <form id="chatForm" class="flex space-x-3">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Tapez votre message ici..." 
                    class="flex-1 p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"
                    autocomplete="off"
                    maxlength="500"
                >
                <button 
                    type="submit" 
                    id="sendBtn"
                    class="bg-gradient-to-r from-blue-600 to-purple-600 text-white w-14 h-14 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            <div class="mt-3 flex justify-between items-center text-sm text-gray-500">
                <button id="clearChat" class="flex items-center space-x-1 hover:text-red-600 transition">
                    <i class="fas fa-trash-alt"></i>
                    <span>Effacer la conversation</span>
                </button>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt"></i>
                    <span>Conversation sécurisée</span>
                </div>
            </div>
        </div>
    </div>


    <script>
    // Éléments DOM
    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatbotPopup = document.getElementById('chatbotPopup');
    const closeChatbot = document.getElementById('closeChatbot');
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingIndicator = document.getElementById('typingIndicator');
    const clearChat = document.getElementById('clearChat');
    const suggestionChips = document.querySelectorAll('.suggestion-chip');
    
    let sessionId = null;
    let isTyping = false;

    // Vérifier le token CSRF
    function getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : null;
    }

    // Ajouter un message au chat
    function addMessage(message, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user-message ml-auto' : 'bot-message'} max-w-[80%] p-4 rounded-2xl shadow-sm`;
        
        const time = new Date().toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        if (isUser) {
            messageDiv.innerHTML = `
                <div class="flex items-center justify-end mb-2">
                    <span class="mr-2 text-xs text-blue-100">${time}</span>
                    <span class="font-semibold text-blue-100">Vous</span>
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center ml-2">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                </div>
                <p class="text-white">${message}</p>
            `;
        } else {
            messageDiv.innerHTML = `
                <div class="flex items-center mb-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <span class="font-semibold text-gray-700">Assistant Virtuel</span>
                    <span class="ml-2 text-xs text-gray-500">${time}</span>
                </div>
                <p class="text-gray-800">${message}</p>
            `;
        }
        
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Afficher un message d'erreur
    function showErrorMessage(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex justify-center mb-4';
        
        const errorBubble = document.createElement('div');
        errorBubble.className = 'bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm max-w-[80%]';
        errorBubble.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        errorDiv.appendChild(errorBubble);
        chatMessages.appendChild(errorDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Afficher l'indicateur de frappe
    function showTypingIndicator() {
        if (!isTyping) {
            isTyping = true;
            typingIndicator.classList.remove('hidden');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    // Cacher l'indicateur de frappe
    function hideTypingIndicator() {
        isTyping = false;
        typingIndicator.classList.add('hidden');
    }

    // Envoyer un message
    async function sendMessage(messageText = null) {
        const message = messageText || messageInput.value.trim();
        if (!message) return;

        // Ajouter le message utilisateur
        addMessage(message, true);
        
        if (!messageText) {
            messageInput.value = '';
            sendBtn.disabled = true;
        }

        // Afficher l'indicateur de frappe
        showTypingIndicator();

        try {
            const csrfToken = getCsrfToken();
            if (!csrfToken) {
                throw new Error('Token de sécurité manquant');
            }

            const payload = { 
                message: message,
                session_id: sessionId 
            };

            const response = await fetch('/chat/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            // Vérifier si la réponse est OK
            if (!response.ok) {
                let errorText = 'Erreur réseau';
                try {
                    const errorData = await response.json();
                    errorText = errorData.message || JSON.stringify(errorData);
                } catch (e) {
                    errorText = await response.text();
                }
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }

            // Vérifier si la réponse est du JSON valide
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Réponse non-JSON du serveur');
            }

            const data = await response.json();

            // Vérifier la structure de la réponse
            if (!data || typeof data !== 'object') {
                throw new Error('Format de réponse invalide');
            }

            if (!data.success) {
                throw new Error(data.message || 'Échec de la requête');
            }

            // Mettre à jour le sessionId
            if (data.session_id) {
                sessionId = data.session_id;
            }

            // Ajouter la réponse du bot
            if (data.response) {
                setTimeout(() => {
                    hideTypingIndicator();
                    addMessage(data.response, false);
                }, 500);
            } else {
                throw new Error('Pas de réponse du bot');
            }

        } catch (error) {
            console.error('Erreur détaillée:', error);
            
            // Messages d'erreur conviviaux
            let userMessage = 'Erreur de connexion. Vérifiez votre connexion internet.';
            
            if (error.message.includes('HTTP 4')) {
                userMessage = 'Erreur de validation. Votre message pourrait être trop long.';
            } else if (error.message.includes('HTTP 5')) {
                userMessage = 'Erreur serveur. Notre équipe technique a été notifiée.';
            } else if (error.message.includes('CSRF') || error.message.includes('token')) {
                userMessage = 'Erreur de sécurité. Veuillez recharger la page.';
                setTimeout(() => location.reload(), 2000);
            }
            
            setTimeout(() => {
                hideTypingIndicator();
                showErrorMessage(userMessage);
            }, 500);
            
        } finally {
            if (!messageText) {
                messageInput.disabled = false;
                messageInput.focus();
            }
        }
    }

    // Effacer la conversation
    async function clearConversation() {
        if (confirm('Voulez-vous vraiment effacer toute la conversation ?')) {
            try {
                const csrfToken = getCsrfToken();
                if (csrfToken) {
                    await fetch('/chat/clear-history', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                }
                
                chatMessages.innerHTML = `
                    <div class="message bot-message max-w-[80%] p-4 rounded-2xl shadow-sm">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-2">
                                <i class="fas fa-robot text-white text-sm"></i>
                            </div>
                            <span class="font-semibold text-gray-700">Assistant Virtuel</span>
                            <span class="ml-2 text-xs text-gray-500">Maintenant</span>
                        </div>
                        <p class="text-gray-800">Bonjour ! 👋 Je suis votre assistant virtuel. Je peux vous aider à découvrir des lieux intéressants, trouver des restaurants, des hôtels, ou calculer des itinéraires. Comment puis-je vous aider aujourd'hui ?</p>
                    </div>
                `;
                
                sessionId = null;
                
                // Afficher un message de confirmation
                showErrorMessage('Conversation effacée avec succès.');
                
            } catch (error) {
                console.error('Erreur lors de l\'effacement:', error);
                showErrorMessage('Erreur lors de l\'effacement de la conversation.');
            }
        }
    }

    // Gestion des suggestions
    suggestionChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const text = chip.textContent.trim();
            let message = '';
            
            switch(text) {
                case 'Restaurants':
                    message = 'Quels sont les meilleurs restaurants près d\'ici ?';
                    break;
                case 'Hôtels':
                    message = 'Pouvez-vous me recommander des hôtels de qualité ?';
                    break;
                case 'Itinéraires':
                    message = 'Comment me rendre au centre-ville ?';
                    break;
                case 'Aide':
                    message = 'De quoi êtes-vous capable ?';
                    break;
                default:
                    message = text;
            }
            
            sendMessage(message);
        });
    });

    // Événements
    chatbotToggle.addEventListener('click', () => {
        chatbotPopup.classList.toggle('hidden');
        chatbotPopup.classList.toggle('flex');
        if (!chatbotPopup.classList.contains('hidden')) {
            messageInput.focus();
        }
    });

    closeChatbot.addEventListener('click', () => {
        chatbotPopup.classList.add('hidden');
        chatbotPopup.classList.remove('flex');
    });

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage();
    });

    clearChat.addEventListener('click', clearConversation);

    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    messageInput.addEventListener('input', () => {
        sendBtn.disabled = messageInput.value.trim().length === 0;
    });

    // Fermer le chat en cliquant en dehors
    document.addEventListener('click', (e) => {
        if (!chatbotPopup.contains(e.target) && 
            !chatbotToggle.contains(e.target) && 
            !chatbotPopup.classList.contains('hidden')) {
            chatbotPopup.classList.add('hidden');
            chatbotPopup.classList.remove('flex');
        }
    });

    // Focus sur le champ de saisie au chargement
    window.addEventListener('DOMContentLoaded', () => {
        sendBtn.disabled = true;
        
        // Vérifier la connexion au serveur
        fetch('/health')
            .then(response => {
                if (!response.ok) {
                    showErrorMessage('Connexion au serveur limitée. Certaines fonctionnalités peuvent être indisponibles.');
                }
            })
            .catch(() => {
                showErrorMessage('Connexion au serveur perdue. Veuillez vérifier votre connexion internet.');
            });
    });

    // Exposer les fonctions globalement pour le débogage
    window.chatbot = {
        sendMessage,
        clearConversation,
        addMessage,
        showErrorMessage
    };
    </script>
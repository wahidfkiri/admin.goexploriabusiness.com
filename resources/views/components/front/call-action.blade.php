<!-- Ajouter ce code juste avant la fermeture du body </body> -->
<div class="contact-modal-overlay" id="contactModal">
    <div class="contact-modal-container">
        <button class="contact-modal-close" id="closeContactModal">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="contact-modal-content">
            <!-- Colonne gauche - Formulaire -->
            <div class="modal-form-column">
                <div class="modal-form-header">
                    <h3><i class="fas fa-envelope-open-text"></i> Contactez-Nous</h3>
                    <p>Remplissez le formulaire et notre équipe vous répondra dans les 24h</p>
                </div>
                
                <form id="modalContactForm" class="modal-contact-form">
                    <div class="modal-form-row">
                        <div class="modal-form-group">
                            <label for="modalFirstName">
                                <i class="fas fa-user"></i> Prénom *
                            </label>
                            <input type="text" id="modalFirstName" class="modal-form-control" placeholder="Votre prénom" required>
                        </div>
                        
                        <div class="modal-form-group">
                            <label for="modalLastName">
                                <i class="fas fa-user"></i> Nom *
                            </label>
                            <input type="text" id="modalLastName" class="modal-form-control" placeholder="Votre nom" required>
                        </div>
                    </div>
                    
                    <div class="modal-form-group">
                        <label for="modalEmail">
                            <i class="fas fa-envelope"></i> Email *
                        </label>
                        <input type="email" id="modalEmail" class="modal-form-control" placeholder="votre.email@exemple.com" required>
                    </div>
                    
                    <div class="modal-form-group">
                        <label for="modalPhone">
                            <i class="fas fa-phone-alt"></i> Téléphone
                        </label>
                        <input type="tel" id="modalPhone" class="modal-form-control" placeholder="+33 1 23 45 67 89">
                    </div>
                    
                    <div class="modal-form-group">
                        <label for="modalSubject">
                            <i class="fas fa-tag"></i> Sujet *
                        </label>
                        <select id="modalSubject" class="modal-form-control" required>
                            <option value="" disabled selected>Sélectionnez un sujet</option>
                            <option value="web">Services Web</option>
                            <option value="tourism">Tourisme & Voyage</option>
                            <option value="business">Business Solutions</option>
                            <option value="support">Support Technique</option>
                            <option value="other">Autre demande</option>
                        </select>
                    </div>
                    
                    <div class="modal-form-group">
                        <label for="modalMessage">
                            <i class="fas fa-comment-dots"></i> Message *
                        </label>
                        <textarea id="modalMessage" class="modal-form-control" placeholder="Décrivez votre projet ou demande..." rows="3" required></textarea>
                    </div>
                    
                    <div class="modal-form-options">
                        <div class="modal-checkbox-group">
                            <input type="checkbox" id="modalNewsletter" checked>
                            <label for="modalNewsletter">Recevoir nos offres spéciales</label>
                        </div>
                        <div class="modal-required-info">
                            * Champs obligatoires
                        </div>
                    </div>
                    
                    <button type="submit" class="modal-submit-btn" id="modalSubmitBtn">
                        <i class="fas fa-paper-plane"></i>
                        <span id="modalSubmitText">Envoyer le message</span>
                        <div class="modal-spinner" id="modalSpinner"></div>
                    </button>
                </form>
                
                <div class="modal-form-footer">
                    <div class="modal-quick-contact">
                        <div class="quick-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>(418) 525-7748</span>
                        </div>
                        <div class="quick-contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Lun-Ven: 9h-18h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne droite - Image/Illustration -->
            <div class="modal-image-column">
                <div class="modal-image-wrapper">
                    <!-- Image de fond -->
                    <div class="modal-image-bg"></div>
                    
                    <!-- Contenu superposé -->
                    <div class="modal-image-content">
                        <div class="modal-image-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4>Votre Partenaire Digital</h4>
                        <p>Go Exploria Business est votre partenaire pour la transformation digitale de votre entreprise.</p>
                        
                        <div class="modal-benefits">
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Réponse sous 24h</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Support personnalisé</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Solutions sur mesure</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Experts certifiés</span>
                            </div>
                        </div>
                        
                        <div class="modal-social-links">
                            <a href="https://www.youtube.com/user/explorezlemonde/videos?view_as=subscriber" target="_blank" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-linkedin"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles pour le modal de contact */
    .contact-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(8px);
        padding: 20px;
    }
    
    .contact-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .contact-modal-container {
        background: white;
        width: 90%;
        max-width: 900px;
        height: auto;
        max-height: 85vh;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        transform: translateY(30px) scale(0.95);
        transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .contact-modal-overlay.active .contact-modal-container {
        transform: translateY(0) scale(1);
    }
    
    .contact-modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .contact-modal-close:hover {
        background: white;
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }
    
    .contact-modal-content {
        display: flex;
        height: 100%;
        min-height: 500px;
    }
    
    /* Colonne formulaire (gauche) */
    .modal-form-column {
        flex: 1;
        padding: 40px;
        background: white;
        overflow-y: auto;
        min-width: 0;
    }
    
    .modal-form-header {
        margin-bottom: 30px;
    }
    
    .modal-form-header h3 {
        color: #2c3e50;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .modal-form-header p {
        color: #7f8c8d;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    .modal-contact-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .modal-form-row {
        display: flex;
        gap: 20px;
    }
    
    .modal-form-group {
        flex: 1;
    }
    
    .modal-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modal-form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #eef2f7;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #f8fafc;
        color: #333;
    }
    
    .modal-form-control:focus {
        outline: none;
        border-color: #3498db;
        background-color: white;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.15);
    }
    
    select.modal-form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233498db' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 45px;
    }
    
    textarea.modal-form-control {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
    }
    
    .modal-form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
    
    .modal-checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #555;
    }
    
    .modal-checkbox-group input {
        width: 18px;
        height: 18px;
        accent-color: #3498db;
    }
    
    .modal-required-info {
        font-size: 0.85rem;
        color: #7f8c8d;
    }
    
    .modal-submit-btn {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        padding: 16px 30px;
        font-size: 1rem;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        width: 100%;
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        position: relative;
    }
    
    .modal-submit-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
    }
    
    .modal-submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .modal-form-footer {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .modal-quick-contact {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .quick-contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #555;
        font-size: 0.9rem;
    }
    
    .quick-contact-item i {
        color: #3498db;
    }
    
    /* Colonne image (droite) */
    .modal-image-column {
        flex: 1;
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        position: relative;
        overflow: hidden;
        min-width: 0;
    }
    
    .modal-image-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
    
    .modal-image-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
        filter: blur(1px);
    }
    
    .modal-image-content {
        position: relative;
        z-index: 2;
        color: white;
        text-align: center;
        max-width: 400px;
    }
    
    .modal-image-icon {
        font-size: 3.5rem;
        margin-bottom: 25px;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .modal-image-content h4 {
        font-size: 1.6rem;
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .modal-image-content p {
        font-size: 1rem;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 30px;
    }
    
    .modal-benefits {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 30px;
        text-align: left;
    }
    
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.95rem;
    }
    
    .benefit-item i {
        color: #2ecc71;
        font-size: 1.1rem;
    }
    
    .modal-social-links {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 25px;
    }
    
    .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50%;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .social-link:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }
    
    /* Spinner de chargement */
    .modal-spinner {
        display: none;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Animation de succès */
    @keyframes modalSuccess {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .modal-success {
        animation: modalSuccess 0.5s ease;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .contact-modal-container {
            max-width: 700px;
        }
        
        .contact-modal-content {
            flex-direction: column;
            max-height: 90vh;
        }
        
        .modal-image-column {
            display: none; /* Cache la colonne image sur tablette */
        }
        
        .modal-form-column {
            padding: 30px;
        }
    }
    
    @media (max-width: 768px) {
        .contact-modal-container {
            width: 95%;
            border-radius: 20px;
        }
        
        .modal-form-column {
            padding: 25px 20px;
        }
        
        .modal-form-row {
            flex-direction: column;
            gap: 0;
        }
        
        .modal-form-header h3 {
            font-size: 1.5rem;
        }
        
        .modal-form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .modal-quick-contact {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
    
    @media (max-width: 480px) {
        .contact-modal-overlay {
            padding: 10px;
        }
        
        .contact-modal-container {
            border-radius: 16px;
            max-height: 95vh;
        }
        
        .modal-form-column {
            padding: 20px 15px;
        }
        
        .modal-submit-btn {
            padding: 14px 20px;
            font-size: 0.95rem;
        }
        
        .contact-modal-close {
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactModal = document.getElementById('contactModal');
    const closeModalBtn = document.getElementById('closeContactModal');
    const contactForm = document.getElementById('modalContactForm');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const submitText = document.getElementById('modalSubmitText');
    const spinner = document.getElementById('modalSpinner');
    
    let modalShown = false;
    let scrollThreshold = 0.8; // 80% de la page
    
    // Vérifier si le popup a déjà été montré dans cette session
    const modalAlreadyShown = sessionStorage.getItem('contactModalShown');
    
    // Ouvrir le popup quand on scroll 80% de la page
    function checkScrollPosition() {
        if (modalShown || modalAlreadyShown) return;
        
        const scrollPosition = window.scrollY + window.innerHeight;
        const pageHeight = document.documentElement.scrollHeight;
        const threshold = pageHeight * scrollThreshold;
        
        // if (scrollPosition >= threshold) {
        //     showModal();
        //     modalShown = true;
        //     // Marquer comme montré dans cette session
        //     sessionStorage.setItem('contactModalShown', 'true');
        //     // Retirer l'écouteur d'événement
        //     window.removeEventListener('scroll', checkScrollPosition);
        // }
    }
    
    function showModal() {
        contactModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = getScrollbarWidth() + 'px'; // Compenser la barre de défilement
    }
    
    function hideModal() {
        contactModal.classList.remove('active');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }
    
    // Calculer la largeur de la barre de défilement
    function getScrollbarWidth() {
        return window.innerWidth - document.documentElement.clientWidth;
    }
    
    // Fermer le modal
    closeModalBtn.addEventListener('click', hideModal);
    
    // Fermer en cliquant en dehors
    contactModal.addEventListener('click', function(e) {
        if (e.target === contactModal) {
            hideModal();
        }
    });
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && contactModal.classList.contains('active')) {
            hideModal();
        }
    });
    
    // Gestion du formulaire
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton et montrer le spinner
        submitBtn.disabled = true;
        submitText.textContent = 'Envoi en cours...';
        spinner.style.display = 'inline-block';
        
        // Simuler l'envoi
        setTimeout(() => {
            // Succès
            submitText.textContent = 'Message envoyé!';
            spinner.style.display = 'none';
            submitBtn.classList.add('modal-success');
            submitBtn.style.background = 'linear-gradient(135deg, #2ecc71, #27ae60)';
            
            // Réinitialiser après 2 secondes
            setTimeout(() => {
                contactForm.reset();
                submitText.textContent = 'Envoyer le message';
                submitBtn.disabled = false;
                submitBtn.classList.remove('modal-success');
                submitBtn.style.background = 'linear-gradient(135deg, #3498db, #2980b9)';
                
                // Fermer le modal après succès
                setTimeout(() => {
                    hideModal();
                }, 1000);
            }, 2000);
        }, 2000);
    });
    
    // Formatage du téléphone
    const phoneInput = document.getElementById('modalPhone');
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 0) {
            if (value.length <= 2) {
                value = '+33 ' + value;
            } else if (value.length <= 4) {
                value = '+33 ' + value.substring(2);
            } else if (value.length <= 6) {
                value = '+33 ' + value.substring(2, 4) + ' ' + value.substring(4);
            } else if (value.length <= 8) {
                value = '+33 ' + value.substring(2, 4) + ' ' + value.substring(4, 6) + ' ' + value.substring(6);
            } else {
                value = '+33 ' + value.substring(2, 4) + ' ' + value.substring(4, 6) + ' ' + value.substring(6, 8) + ' ' + value.substring(8, 10);
            }
        }
        
        e.target.value = value;
    });
    
    // Observer le scroll
    window.addEventListener('scroll', checkScrollPosition);
    
    // Vérifier au chargement si on est déjà à 80%
    setTimeout(checkScrollPosition, 1000);
    
    // Option: Ajouter un bouton pour ouvrir manuellement le modal
    const openModalBtn = document.createElement('button');
    openModalBtn.className = 'btn btn-primary btn-sm';
    openModalBtn.innerHTML = '<i class="fas fa-envelope me-1"></i> Contact';
    openModalBtn.style.position = 'fixed';
    openModalBtn.style.bottom = '20px';
    openModalBtn.style.left = '20px';
    openModalBtn.style.zIndex = '9998';
    openModalBtn.style.padding = '10px 20px';
    openModalBtn.style.borderRadius = '50px';
    openModalBtn.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
    
    openModalBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showModal();
    });
    
    // Ajouter le bouton au DOM
    document.body.appendChild(openModalBtn);
});
</script>
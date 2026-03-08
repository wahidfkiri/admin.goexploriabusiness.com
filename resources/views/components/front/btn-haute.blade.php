<style>
        /* Style pour le bouton retour en haut */
        .back-to-top {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            transform: translateY(-10px);
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .back-to-top:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        }
        
        /* Styles pour le méga-menu */
        .mega-menu-container {
            position: relative;
        }
        
        .mega-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            padding: 30px;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transform: translateY(15px);
            transition: all 0.3s ease;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }
        
        .mega-menu-container:hover .mega-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .mega-menu-column h4 {
            color: var(--dark-color);
            font-size: 1.1rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
        }
        
        .mega-menu-link {
            display: flex;
            align-items: flex-start;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: all 0.2s ease;
            background: #f9f9f9;
        }
        
        .mega-menu-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }
        
        .mega-menu-icon {
            width: 40px;
            height: 40px;
            background: var(--accent-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
        }
        
        .mega-menu-text {
            flex: 1;
        }
        
        .mega-menu-text h6 {
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .mega-menu-text p {
            font-size: 0.8rem;
            opacity: 0.8;
            margin: 0;
        }
        
        .mega-menu-highlight {
            grid-column: span 2;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
            padding: 25px;
            color: white;
            margin-top: 10px;
        }
        
        .mega-menu-highlight h4 {
            color: white;
            border-bottom-color: rgba(255,255,255,0.3);
        }
        
        .highlight-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
        }
        
        .highlight-icon {
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .back-to-top {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
                top: 15px;
                right: 15px;
            }
            
            .mega-menu {
                width: 95vw;
                left: -50px;
                grid-template-columns: repeat(2, 1fr);
                padding: 20px;
            }
            
            .mega-menu-highlight {
                grid-column: span 2;
            }
        }
        
        @media (max-width: 576px) {
            .mega-menu {
                grid-template-columns: 1fr;
            }
            
            .mega-menu-highlight {
                grid-column: span 1;
            }
        }
    </style>
     <!-- Bouton retour en haut -->
    <button class="back-to-top" id="backToTop" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>
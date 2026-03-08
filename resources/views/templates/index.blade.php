<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Templates - GrapesJS + Laravel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .template-card {
            transition: all 0.3s ease;
        }
        
        .template-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .preview-container {
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow: hidden;
            position: relative;
        }
        
        .preview-content {
            transform: scale(0.3);
            transform-origin: top left;
            width: 333%;
            height: 333%;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="container mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-paint-brush text-2xl text-purple-600"></i>
                        <span class="text-xl font-bold text-gray-800">GrapesJS Templates</span>
                    </div>
                    <div class="space-x-4">
                        <a href="{{ route('editor') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-plus mr-2"></i>Nouveau
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <div class="glass-card rounded-2xl p-8 mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes Templates</h1>
                <p class="text-gray-600">Gérez et chargez vos templates sauvegardés</p>
            </div>

            <div id="templatesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Templates chargés dynamiquement -->
            </div>

            <!-- État vide -->
            <div id="emptyState" class="hidden text-center py-16">
                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-semibold text-gray-600 mb-2">Aucun template</h3>
                <p class="text-gray-500 mb-6">Créez votre premier template avec l'éditeur</p>
                <a href="{{ route('editor') }}" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition inline-block">
                    <i class="fas fa-paint-brush mr-2"></i>Commencer
                </a>
            </div>
        </div>
    </div>

    <script>
        // Charger les templates au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            loadTemplates();
        });

        async function loadTemplates() {
            try {
                const response = await fetch('{{ route("templates.index") }}');
                const templates = await response.json();
                
                const container = document.getElementById('templatesContainer');
                const emptyState = document.getElementById('emptyState');
                
                if (templates.length === 0) {
                    container.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                    return;
                }
                
                container.innerHTML = templates.map(template => `
                    <div class="template-card glass-card rounded-xl overflow-hidden">
                        <div class="preview-container">
                            <div class="preview-content">
                                ${template.html_content}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">${template.name}</h3>
                                    ${template.description ? `<p class="text-gray-600 mt-1">${template.description}</p>` : ''}
                                </div>
                                <span class="text-sm text-gray-500">
                                    ${new Date(template.created_at).toLocaleDateString('fr-FR')}
                                </span>
                            </div>
                            <div class="flex space-x-3">
                                <button onclick="loadTemplate(${template.id})" 
                                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                    <i class="fas fa-edit mr-2"></i>Charger
                                </button>
                                <button onclick="exportTemplate(${template.id})" 
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button onclick="deleteTemplate(${template.id})" 
                                        class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                container.classList.remove('hidden');
                emptyState.classList.add('hidden');
            } catch (error) {
                console.error('Error:', error);
                alert('Erreur de chargement des templates');
            }
        }

        async function loadTemplate(id) {
            try {
                const response = await fetch(`/api/templates/${id}`);
                const template = await response.json();
                
                // Stocker dans localStorage pour l'éditeur
                localStorage.setItem('loadTemplate', JSON.stringify({
                    html: template.html_content,
                    css: template.css_content,
                    name: template.name
                }));
                
                // Rediriger vers l'éditeur
                window.location.href = '{{ route("editor") }}?load=' + id;
            } catch (error) {
                console.error('Error:', error);
                alert('Erreur de chargement du template');
            }
        }

        async function exportTemplate(id) {
            window.open(`/api/templates/${id}/export`, '_blank');
        }

        async function deleteTemplate(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce template ?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/templates/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    loadTemplates(); // Recharger la liste
                    alert('Template supprimé avec succès');
                } else {
                    alert('Erreur lors de la suppression');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Erreur de connexion');
            }
        }
    </script>
</body>
</html>
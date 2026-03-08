{{-- resources/views/templates/ai/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Templates Générés par IA')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-robot text-primary"></i> 
        Templates IA
        <span class="badge bg-gradient-primary">{{ $templates->total() }}</span>
    </h1>
    
    <div class="btn-group">
        <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-file-code"></i> Tous les Templates
        </a>
        <a href="{{ route('ai.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Nouveau Template IA
        </a>
    </div>
</div>

<div class="row">
    @forelse($templates as $template)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 text-truncate">
                        <i class="fas fa-robot"></i> {{ Str::limit($template->name, 25) }}
                    </h6>
                    <span class="badge bg-light text-dark">
                        AI
                    </span>
                </div>
                
                <div class="card-body">
                    @if($template->thumbnail)
                        <img src="{{ Storage::url($template->thumbnail) }}" 
                             class="img-fluid rounded mb-3" 
                             alt="{{ $template->name }}"
                             style="height: 150px; width: 100%; object-fit: cover;">
                    @else
                        <div class="bg-gradient-light text-center py-4 mb-3 rounded">
                            <i class="fas fa-code fa-3x text-primary"></i>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        @if($template->description['style'] ?? false)
                            <span class="badge bg-info me-1">
                                {{ $template->description['style'] }}
                            </span>
                        @endif
                        
                        @if($template->description['layout'] ?? false)
                            <span class="badge bg-success me-1">
                                {{ $template->description['layout'] }}
                            </span>
                        @endif
                        
                        @if($template->description['framework'] ?? false)
                            <span class="badge bg-warning">
                                {{ $template->description['framework'] }}
                            </span>
                        @endif
                    </div>
                    
                    <p class="small text-muted">
                        <i class="fas fa-calendar"></i> 
                        {{ $template->created_at->diffForHumans() }}
                    </p>
                    
                    @if($template->description['generated_by'] ?? false)
                        <p class="small">
                            <i class="fas fa-brain"></i> 
                            {{ strtoupper($template->description['model'] ?? 'gpt-4') }}
                        </p>
                    @endif
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('templates.show', $template) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                        
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-info dropdown-toggle" 
                                    type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" 
                                       onclick="optimizeTemplate({{ $template->id }})">
                                        <i class="fas fa-magic"></i> Optimiser avec IA
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" 
                                       href="{{ route('ai.templates.create') }}?clone={{ $template->id }}">
                                        <i class="fas fa-copy"></i> Générer une variante
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('templates.destroy', $template) }}" 
                                          method="POST" id="delete-form-{{ $template->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" 
                                                onclick="if(confirm('Supprimer ce template IA?')) document.getElementById('delete-form-{{ $template->id }}').submit()">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-robot fa-4x text-muted mb-4"></i>
                <h3>Aucun template généré par IA</h3>
                <p class="text-muted mb-4">
                    Commencez par générer votre premier template avec l'intelligence artificielle
                </p>
                <a href="{{ route('ai.templates.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-robot"></i> Générer un Template IA
                </a>
            </div>
        </div>
    @endforelse
</div>

@if($templates->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $templates->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
function optimizeTemplate(templateId) {
    if (confirm('Optimiser ce template avec OpenAI? Cela générera une nouvelle version améliorée.')) {
        const modal = new bootstrap.Modal(document.getElementById('optimizeModal'));
        modal.show();
        
        fetch(`/ai-templates/${templateId}/optimize`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                instructions: [
                    'Optimiser les performances',
                    'Améliorer le responsive design',
                    'Simplifier le CSS',
                    'Ajouter des commentaires'
                ]
            })
        })
        .then(response => response.json())
        .then(data => {
            modal.hide();
            
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            modal.hide();
            alert('Erreur: ' + error.message);
        });
    }
}
</script>
@endpush
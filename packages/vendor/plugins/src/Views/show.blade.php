@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('vendor/plugins/css/style.css') }}">

<main class="dashboard-content">
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="{{ $plugin->icon ?: 'fas fa-puzzle-piece' }}"></i></span>
            Détail Module: {{ $plugin->name }}
        </h1>
        <div class="page-actions">
            <a href="{{ route('modules.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="main-card-modern">
        <div class="card-body-modern">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-4">
                        @if($plugin->main_media_type === 'video' && !empty($plugin->main_video_path))
                            <video controls style="width:100%;max-height:420px;border-radius:12px;background:#000;">
                                <source src="{{ asset('storage/' . $plugin->main_video_path) }}" type="video/mp4">
                                Votre navigateur ne supporte pas la vidéo.
                            </video>
                        @elseif(!empty($plugin->main_image_path))
                            <img src="{{ asset('storage/' . $plugin->main_image_path) }}" alt="{{ $plugin->name }}" style="width:100%;max-height:420px;object-fit:cover;border-radius:12px;">
                        @else
                            <div class="p-5 text-center border rounded-3 text-muted">Aucun média principal</div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-3">Description</h4>
                        <div class="border rounded-3 p-3 bg-white">
                            {!! $plugin->description !!}
                        </div>
                    </div>

                    @if(!empty($plugin->gallery_images) && is_array($plugin->gallery_images))
                        <div>
                            <h4 class="mb-3">Galerie d'images</h4>
                            <div class="row g-3">
                                @foreach($plugin->gallery_images as $img)
                                    <div class="col-md-4 col-sm-6">
                                        <img src="{{ asset('storage/' . $img) }}" alt="gallery" style="width:100%;height:180px;object-fit:cover;border-radius:10px;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="border rounded-3 p-3 bg-white mb-3">
                        <h5 class="mb-3">Informations</h5>
                        <p class="mb-2"><strong>Version:</strong> {{ $plugin->version }}</p>
                        <p class="mb-2"><strong>Auteur:</strong> {{ $plugin->author }}</p>
                        <p class="mb-2"><strong>Catégorie:</strong> {{ optional($plugin->category)->name ?: '-' }}</p>
                        <p class="mb-2"><strong>Statut:</strong> {{ $plugin->status }}</p>
                        <p class="mb-0"><strong>Type:</strong> {{ $plugin->type }}</p>
                    </div>

                    @if($plugin->documentation_url)
                        <a href="{{ $plugin->documentation_url }}" target="_blank" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-book me-2"></i>Documentation
                        </a>
                    @endif

                    @if($plugin->demo_url)
                        <a href="{{ $plugin->demo_url }}" target="_blank" class="btn btn-outline-success w-100">
                            <i class="fas fa-external-link-alt me-2"></i>Démo
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

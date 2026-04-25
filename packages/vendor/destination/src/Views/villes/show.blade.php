@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <div class="page-header">
        <h1 class="page-title">
            <span class="page-title-icon"><i class="fas fa-city"></i></span>
            Détails de la Ville
        </h1>
        <div class="page-actions">
            <a href="{{ route('villes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
            </a>
            <a href="{{ route('villes.index') }}?open_edit={{ $ville->id }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
        </div>
    </div>

    <div class="main-card-modern">
        <div class="card-header-modern">
            <h3 class="card-title-modern">{{ $ville->name }}</h3>
            <div>
                @if($ville->status)
                    <span class="badge bg-primary">{{ $ville->status }}</span>
                @endif
                @if($ville->classification)
                    <span class="badge bg-secondary">{{ $ville->classification }}</span>
                @endif
            </div>
        </div>

        <div class="card-body-modern">
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Code:</strong> {{ $ville->code ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Pays:</strong> {{ $ville->country->name ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Province:</strong> {{ $ville->province->name ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Région:</strong> {{ $ville->region->name ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Secteur:</strong> {{ $ville->secteur->name ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Maire:</strong> {{ $ville->mayor ?? 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Population:</strong> {{ $statistics['total_population'] ? number_format($statistics['total_population']) : 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Superficie:</strong> {{ $statistics['total_area'] ? number_format($statistics['total_area'], 2) . ' km²' : 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Densité:</strong> {{ $statistics['density'] ? number_format($statistics['density'], 2) . ' hab/km²' : 'N/A' }}
                </div>
                <div class="col-md-6">
                    <strong>Ménages:</strong> {{ $statistics['households'] ? number_format($statistics['households']) : 'N/A' }}
                </div>
            </div>

            @if($ville->description)
                <hr>
                <h5>Description</h5>
                <p class="mb-0">{{ $ville->description }}</p>
            @endif
        </div>
    </div>
</main>
@endsection


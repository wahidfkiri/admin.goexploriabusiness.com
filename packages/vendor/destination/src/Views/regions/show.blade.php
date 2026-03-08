@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-mountain"></i></span>
                Détails de la Région
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('regions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRegionModal">
                    <i class="fas fa-edit me-2"></i>Modifier
                </button>
            </div>
        </div>

        <!-- Region Header -->
        <div class="region-header-modern">
            <div class="region-header-left">
                <div class="region-flag-large">
                    @if($region->flag)
                        <img src="{{ $region->flag }}" alt="{{ $region->name }}" class="flag-img-large">
                    @else
                        <i class="fas fa-mountain"></i>
                    @endif
                </div>
                <div class="region-header-info">
                    <h1 class="region-name-large">{{ $region->name }}</h1>
                    <div class="region-metadata">
                        <span class="badge bg-primary">{{ $region->code }}</span>
                        <span class="badge bg-secondary">{{ $region->province->name }}</span>
                        <span class="badge bg-info">{{ $region->classification }}</span>
                        @if($region->capital)
                            <span class="badge bg-success">Capitale: {{ $region->capital }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="region-header-right">
                <div class="region-coordinates">
                    @if($region->latitude && $region->longitude)
                        <a href="{{ $region->google_maps_url }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $region->coordinates }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats-grid">
            <div class="quick-stat-card">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, var(--accent-color), #06b48a);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ number_format($region->population) }}</div>
                    <div class="quick-stat-label">Population</div>
                </div>
            </div>
            
            <div class="quick-stat-card">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, #ffd166, #ffb347);">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ number_format($region->area, 2) }} km²</div>
                    <div class="quick-stat-label">Superficie</div>
                </div>
            </div>
            
            <div class="quick-stat-card">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, #3a56e4, #2947c2);">
                    <i class="fas fa-building"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $region->municipalities_count }}</div>
                    <div class="quick-stat-label">Municipalités</div>
                </div>
            </div>
            
            <div class="quick-stat-card">
                <div class="quick-stat-icon" style="background: linear-gradient(135deg, #ef476f, #d4335f);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="quick-stat-content">
                    <div class="quick-stat-value">{{ $region->formatted_population_density }}</div>
                    <div class="quick-stat-label">Densité</div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-modern">
            <ul class="nav nav-tabs-modern" id="regionTabs" role="tablist">
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                        <i class="fas fa-info-circle me-2"></i>Aperçu
                    </button>
                </li>
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern" id="geography-tab" data-bs-toggle="tab" data-bs-target="#geography" type="button">
                        <i class="fas fa-mountain me-2"></i>Géographie
                    </button>
                </li>
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern" id="economy-tab" data-bs-toggle="tab" data-bs-target="#economy" type="button">
                        <i class="fas fa-chart-line me-2"></i>Économie
                    </button>
                </li>
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern" id="tourism-tab" data-bs-toggle="tab" data-bs-target="#tourism" type="button">
                        <i class="fas fa-camera me-2"></i>Tourisme
                    </button>
                </li>
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern" id="cities-tab" data-bs-toggle="tab" data-bs-target="#cities" type="button">
                        <i class="fas fa-city me-2"></i>Villes
                    </button>
                </li>
                <li class="nav-item-modern" role="presentation">
                    <button class="nav-link-modern" id="sectors-tab" data-bs-toggle="tab" data-bs-target="#sectors" type="button">
                        <i class="fas fa-map me-2"></i>Secteurs
                    </button>
                </li>
            </ul>

            <div class="tab-content-modern" id="regionTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane-modern fade show active" id="overview" role="tabpanel">
                    <div class="tab-content-inner">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card-modern">
                                    <div class="card-header-modern">
                                        <h3 class="card-title-modern">Description</h3>
                                    </div>
                                    <div class="card-body-modern">
                                        @if($region->description)
                                            <p class="region-description">{{ $region->description }}</p>
                                        @else
                                            <div class="empty-state-text">
                                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                <p>Aucune description disponible pour cette région.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-modern mt-4">
                                    <div class="card-header-modern">
                                        <h3 class="card-title-modern">Informations générales</h3>
                                    </div>
                                    <div class="card-body-modern">
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <div class="info-label">Province</div>
                                                <div class="info-value">
                                                    <a href="{{ route('provinces.show', $region->province_id) }}" class="info-link">
                                                        <i class="fas fa-map-marked-alt me-2"></i>
                                                        {{ $region->province->name }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Classification</div>
                                                <div class="info-value">
                                                    <span class="classification-badge {{ getClassificationClass($region->classification) }}">
                                                        {{ $region->classification }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Fuseau horaire</div>
                                                <div class="info-value">{{ $region->timezone ?? 'Non spécifié' }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Plus grande ville</div>
                                                <div class="info-value">{{ $region->largest_city ?? 'Non spécifiée' }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Date de création</div>
                                                <div class="info-value">{{ $region->created_at->format('d/m/Y') }}</div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Dernière mise à jour</div>
                                                <div class="info-value">{{ $region->updated_at->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card-modern">
                                    <div class="card-header-modern">
                                        <h3 class="card-title-modern">Statistiques</h3>
                                    </div>
                                    <div class="card-body-modern">
                                        <div class="statistics-grid">
                                            <div class="stat-item">
                                                <div class="stat-icon">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ number_format($region->population) }}</div>
                                                    <div class="stat-label">Population</div>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon">
                                                    <i class="fas fa-globe"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ number_format($region->area, 2) }} km²</div>
                                                    <div class="stat-label">Superficie</div>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon">
                                                    <i class="fas fa-chart-line"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ $region->formatted_population_density }}</div>
                                                    <div class="stat-label">Densité</div>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon">
                                                    <i class="fas fa-city"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ $statistics['total_cities'] ?? 0 }}</div>
                                                    <div class="stat-label">Villes</div>
                                                </div>
                                            </div>
                                            <div class="stat-item">
                                                <div class="stat-icon">
                                                    <i class="fas fa-map-marked-alt"></i>
                                                </div>
                                                <div class="stat-content">
                                                    <div class="stat-value">{{ $statistics['total_secteurs'] ?? 0 }}</div>
                                                    <div class="stat-label">Secteurs</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($region->latitude && $region->longitude)
                                <div class="card-modern mt-4">
                                    <div class="card-header-modern">
                                        <h3 class="card-title-modern">Localisation</h3>
                                    </div>
                                    <div class="card-body-modern">
                                        <div class="map-placeholder" id="mapPlaceholder">
                                            <div class="map-coordinates">
                                                <i class="fas fa-map-marker-alt"></i>
                                                {{ $region->coordinates }}
                                            </div>
                                            <a href="{{ $region->google_maps_url }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">
                                                <i class="fas fa-external-link-alt me-2"></i>
                                                Ouvrir dans Google Maps
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Geography Tab -->
                <div class="tab-pane-modern fade" id="geography" role="tabpanel">
                    <div class="tab-content-inner">
                        @if($region->geography)
                            <div class="card-modern">
                                <div class="card-body-modern">
                                    <h4 class="section-title-modern">Description géographique</h4>
                                    <p class="region-geography">{{ $region->geography }}</p>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-full">
                                <i class="fas fa-mountain fa-3x mb-4"></i>
                                <h3>Aucune information géographique</h3>
                                <p>Ajoutez une description géographique pour cette région.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRegionModal">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Economy Tab -->
                <div class="tab-pane-modern fade" id="economy" role="tabpanel">
                    <div class="tab-content-inner">
                        @if($region->economy)
                            <div class="card-modern">
                                <div class="card-body-modern">
                                    <h4 class="section-title-modern">Secteurs économiques</h4>
                                    <p class="region-economy">{{ $region->economy }}</p>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-full">
                                <i class="fas fa-chart-line fa-3x mb-4"></i>
                                <h3>Aucune information économique</h3>
                                <p>Ajoutez une description économique pour cette région.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRegionModal">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tourism Tab -->
                <div class="tab-pane-modern fade" id="tourism" role="tabpanel">
                    <div class="tab-content-inner">
                        @if($region->tourism)
                            <div class="card-modern">
                                <div class="card-body-modern">
                                    <h4 class="section-title-modern">Attractions touristiques</h4>
                                    <p class="region-tourism">{{ $region->tourism }}</p>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-full">
                                <i class="fas fa-camera fa-3x mb-4"></i>
                                <h3>Aucune information touristique</h3>
                                <p>Ajoutez une description touristique pour cette région.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editRegionModal">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cities Tab -->
                <div class="tab-pane-modern fade" id="cities" role="tabpanel">
                    <div class="tab-content-inner">
                        @if($statistics['total_cities'] > 0)
                            <div class="card-modern">
                                <div class="card-header-modern">
                                    <h3 class="card-title-modern">Villes de la région</h3>
                                    <a href="{{ route('cities.create') }}?region_id={{ $region->id }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-2"></i>Ajouter une ville
                                    </a>
                                </div>
                                <div class="card-body-modern">
                                    <div class="table-container-modern">
                                        <table class="modern-table">
                                            <thead>
                                                <tr>
                                                    <th>Ville</th>
                                                    <th>Population</th>
                                                    <th>Superficie</th>
                                                    <th>Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($region->cities as $city)
                                                    <tr>
                                                        <td>{{ $city->name }}</td>
                                                        <td>{{ number_format($city->population) }}</td>
                                                        <td>{{ number_format($city->area, 2) }} km²</td>
                                                        <td>{{ $city->type }}</td>
                                                        <td>
                                                            <a href="{{ route('cities.show', $city->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-full">
                                <i class="fas fa-city fa-3x mb-4"></i>
                                <h3>Aucune ville dans cette région</h3>
                                <p>Commencez par ajouter des villes à cette région.</p>
                                <a href="{{ route('cities.create') }}?region_id={{ $region->id }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Ajouter une ville
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sectors Tab -->
                <div class="tab-pane-modern fade" id="sectors" role="tabpanel">
                    <div class="tab-content-inner">
                        @if($statistics['total_secteurs'] > 0)
                            <div class="card-modern">
                                <div class="card-header-modern">
                                    <h3 class="card-title-modern">Secteurs de la région</h3>
                                    <a href="{{ route('secteurs.create') }}?region_id={{ $region->id }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-2"></i>Ajouter un secteur
                                    </a>
                                </div>
                                <div class="card-body-modern">
                                    <div class="table-container-modern">
                                        <table class="modern-table">
                                            <thead>
                                                <tr>
                                                    <th>Secteur</th>
                                                    <th>Description</th>
                                                    <th>Type</th>
                                                    <th>Évaluation</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($region->secteurs as $secteur)
                                                    <tr>
                                                        <td>{{ $secteur->name }}</td>
                                                        <td>{{ Str::limit($secteur->description, 50) }}</td>
                                                        <td>{{ $secteur->type }}</td>
                                                        <td>
                                                            <div class="rating-stars">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $secteur->rating)
                                                                        <i class="fas fa-star text-warning"></i>
                                                                    @else
                                                                        <i class="far fa-star text-muted"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('secteurs.show', $secteur->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="empty-state-full">
                                <i class="fas fa-map fa-3x mb-4"></i>
                                <h3>Aucun secteur dans cette région</h3>
                                <p>Commencez par ajouter des secteurs à cette région.</p>
                                <a href="{{ route('secteurs.create') }}?region_id={{ $region->id }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Ajouter un secteur
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Region Modal (Reuse from index) -->
    @include('regions.edit-modal')

    <!-- Styles spécifiques pour la page show -->
    <style>
        .region-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .region-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .region-flag-large {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 1px solid #eaeaea;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .region-flag-large i {
            font-size: 2.5rem;
            color: var(--primary-color);
        }

        .region-name-large {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0 0 10px 0;
        }

        .region-metadata {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quick-stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
            transition: transform 0.3s ease;
        }

        .quick-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .quick-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .quick-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
        }

        .quick-stat-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tabs-modern {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .nav-tabs-modern {
            display: flex;
            background: #f8f9fa;
            padding: 0 20px;
            border-bottom: 1px solid #eaeaea;
        }

        .nav-item-modern {
            margin: 0;
        }

        .nav-link-modern {
            padding: 15px 20px;
            border: none;
            background: none;
            color: #666;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .nav-link-modern:hover,
        .nav-link-modern.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: white;
        }

        .tab-content-modern {
            padding: 0;
        }

        .tab-pane-modern {
            display: none;
        }

        .tab-pane-modern.active {
            display: block;
        }

        .tab-content-inner {
            padding: 30px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }

        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .info-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
            color: var(--text-color);
        }

        .info-link {
            color: var(--primary-color);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .info-link:hover {
            text-decoration: underline;
        }

        .statistics-grid {
            display: grid;
            gap: 15px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: white;
        }

        .stat-value {
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
        }

        .map-placeholder {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #dee2e6;
        }

        .map-coordinates {
            font-family: monospace;
            background: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .section-title-modern {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .empty-state-full {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .empty-state-full i {
            color: #dee2e6;
        }

        .empty-state-text {
            text-align: center;
            padding: 30px;
            color: #666;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        @media (max-width: 768px) {
            .region-header-modern {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .region-header-left {
                flex-direction: column;
                text-align: center;
            }
            
            .region-metadata {
                justify-content: center;
            }
            
            .nav-tabs-modern {
                overflow-x: auto;
                flex-wrap: nowrap;
            }
            
            .nav-link-modern {
                white-space: nowrap;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .quick-stats-grid {
                grid-template-columns: 1fr;
            }
            
            .region-name-large {
                font-size: 1.5rem;
            }
        }
    </style>

    @php
    function getClassificationClass($classification) {
        if (!$classification) return '';
        
        $classification = strtolower($classification);
        
        if (strpos($classification, 'administrative') !== false) return 'classification-admin';
        if (strpos($classification, 'touristique') !== false) return 'classification-tourist';
        if (strpos($classification, 'naturelle') !== false) return 'classification-nature';
        if (strpos($classification, 'urbaine') !== false) return 'classification-urban';
        if (strpos($classification, 'rurale') !== false) return 'classification-rural';
        
        return '';
    }
    @endphp
@endsection
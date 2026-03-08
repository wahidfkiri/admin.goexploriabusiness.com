@extends('layouts.app')

@section('content')
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Container -->
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <span class="page-title-icon"><i class="fas fa-hiking"></i></span>
                    Gestion des Activités
                    @if($country)
                    <small class="text-muted">pour {{ $country->name }}</small>
                    @endif
                </h1>
                
            </div>
            
            <!-- Simple Tabs -->
            <div class="mb-4">
                <ul class="nav nav-tabs" id="activitiesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="activities-tab" data-bs-toggle="tab" 
                                data-bs-target="#activities-tab-pane" type="button" role="tab">
                            <i class="fas fa-hiking me-2"></i>Activités
                            <span class="badge bg-primary ms-2" id="activitiesCount">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" 
                                data-bs-target="#media-tab-pane" type="button" role="tab">
                            <i class="fas fa-images me-2"></i>Médias
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
    <button class="nav-link" id="places-tab" data-bs-toggle="tab" 
            data-bs-target="#places-tab-pane" type="button" role="tab">
        <i class="fas fa-map-marker-alt me-2"></i>Lieux
        <span class="badge bg-success ms-2" id="placesCount">0</span>
    </button>
</li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="events-tab" data-bs-toggle="tab" 
                                data-bs-target="#events-tab-pane" type="button" role="tab">
                            <i class="fas fa-calendar-alt me-2"></i>Événements
                        </button>
                    </li>
                </ul>
            </div>
            
            <!-- Tab Content -->
            <div class="tab-content" id="activitiesTabContent">
                
                @include('destination::countries.pages.tabs.activity')
                
               @include('destination::countries.pages.tabs.media')
               @include('destination::countries.pages.tabs.maps')
               @include('destination::countries.pages.tabs.event')
            </div>
        </div>
    </main>
    
    
@endsection
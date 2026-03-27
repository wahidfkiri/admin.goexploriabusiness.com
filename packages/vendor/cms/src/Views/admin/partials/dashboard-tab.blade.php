<div class="tab-pane fade show active" id="v-pills-dashboard" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-tachometer-alt me-2" style="color: var(--primary-color);"></i>
            Tableau de bord
        </h3>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-check-circle text-success"></i>
                    <h5>Statut du site</h5>
                </div>
                <div class="info-card-body">
                    <p>Thème actif: <strong>{{ $stats['active_theme']->name ?? 'Aucun thème actif' }}</strong></p>
                    <p>Dernière mise à jour: <strong>{{ now()->format('d/m/Y H:i') }}</strong></p>
                    <p>Pages publiées: <strong>{{ $stats['published_pages'] ?? 0 }}</strong></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-globe text-primary"></i>
                    <h5>URL du site</h5>
                </div>
                <div class="info-card-body">
                    <p><a href="{{ route('cms.company.home', ['etablissementId' => $stats['etablissement']->id]) }}" target="_blank">
                        {{ route('cms.company.home', ['etablissementId' => $stats['etablissement']->id]) }}
                    </a></p>
                    <p class="text-muted small">Voir le site en direct</p>
                    <hr>
                    <p>Page d'accueil: <strong>{{ $stats['homepage'] ?? 'Non définie' }}</strong></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="recent-pages-section mt-4">
        <h5>Pages récentes</h5>
        <div class="table-container-modern">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Slug</th>
                        <th>Statut</th>
                        <th>Dernière modification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['recent_pages'] ?? [] as $page)
                    <tr>
                        <td>
                            <i class="fas fa-file-alt me-2" style="color: var(--primary-color);"></i>
                            {{ $page->title }}
                        </td>
                        <td><code>{{ $page->slug }}</code></td>
                        <td>
                            @if($page->status === 'published')
                                <span class="badge bg-success">Publiée</span>
                            @else
                                <span class="badge bg-warning">Brouillon</span>
                            @endif
                        </td>
                        <td>{{ $page->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('cms.admin.pages.edit', ['etablissementId' => $stats['etablissement']->id, 'id' => $page->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('cms.company.page', ['etablissementId' => $stats['etablissement']->id, 'slug' => $page->slug]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Aucune page créée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
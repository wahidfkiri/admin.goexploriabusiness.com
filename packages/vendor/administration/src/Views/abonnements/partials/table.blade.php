<div class="table-container-modern">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Référence</th>
                <th>Établissement</th>
                <th>Plan</th>
                <th>Montant</th>
                <th>Période</th>
                <th>Statut</th>
                <th>Paiement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($abonnements as $abonnement)
            <tr>
                <td>
                    <strong>{{ $abonnement->reference }}</strong>
                    <br>
                    <small class="text-muted">{{ $abonnement->created_at->format('d/m/Y') }}</small>
                </td>
                <td>
                    <strong>{{ $abonnement->etablissement->name }}</strong>
                    <br>
                    <small>{{ $abonnement->etablissement->ville ?? '' }}</small>
                </td>
                <td>
                    <span class="badge bg-info">{{ $abonnement->plan->name }}</span>
                </td>
                <td>
                    <strong>{{ number_format($abonnement->amount_paid, 0, ',', ' ') }} {{ $abonnement->currency }}</strong>
                </td>
                <td>
                    {{ $abonnement->start_date->format('d/m/Y') }}
                    <br>
                    <small>→ {{ $abonnement->end_date->format('d/m/Y') }}</small>
                    @if($abonnement->isActive())
                        <br>
                        <small class="text-success">+{{ $abonnement->daysRemaining() }} jours restants</small>
                    @endif
                </td>
                <td>
                    @if($abonnement->status === 'active')
                        <span class="badge bg-success">Actif</span>
                    @elseif($abonnement->status === 'expired')
                        <span class="badge bg-danger">Expiré</span>
                    @elseif($abonnement->status === 'cancelled')
                        <span class="badge bg-warning">Annulé</span>
                    @else
                        <span class="badge bg-secondary">En attente</span>
                    @endif
                </td>
                <td>
                    @if($abonnement->payment_status === 'paid')
                        <span class="badge bg-success">Payé</span>
                    @elseif($abonnement->payment_status === 'unpaid')
                        <span class="badge bg-danger">Impayé</span>
                    @elseif($abonnement->payment_status === 'partial')
                        <span class="badge bg-warning">Partiel</span>
                    @else
                        <span class="badge bg-secondary">Remboursé</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('abonnements.edit', $abonnement->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteAbonnement({{ $abonnement->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                        @if($abonnement->status === 'active')
                            <button class="btn btn-sm btn-outline-warning cancel-subscription" data-id="{{ $abonnement->id }}">
                                <i class="fas fa-ban"></i>
                            </button>
                        @endif
                        @if($abonnement->isExpired())
                            <button class="btn btn-sm btn-outline-success renew-subscription" data-id="{{ $abonnement->id }}">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        @endif
                        <a href="{{ route('abonnements.historique', $abonnement->etablissement_id) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-history"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p>Aucun abonnement trouvé</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container-modern">
    {{ $abonnements->links() }}
</div>

<script>
function deleteAbonnement(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?')) {
        $.ajax({
            url: '/admin/abonnements/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            }
        });
    }
}
</script>
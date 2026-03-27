<div class="tab-pane fade" id="v-pills-subscribers" role="tabpanel">
    <div class="tab-content-header">
        <h3 class="tab-title">
            <i class="fas fa-users me-2" style="color: #06d6a0;"></i>
            Abonnés
        </h3>
        <div>
            <button class="btn btn-outline-primary btn-sm" id="exportSubscribersBtn">
                <i class="fas fa-download me-1"></i>Exporter
            </button>
            <button class="btn btn-outline-primary btn-sm ms-2" id="importSubscribersBtn">
                <i class="fas fa-upload me-1"></i>Importer
            </button>
        </div>
    </div>
    
    <div class="table-container-modern">
        <table class="modern-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Email</th>
                    <th>Date d'abonnement</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
            </thead>
            <tbody id="subscribersTableBody">
                @forelse($stats['subscribers'] ?? [] as $subscriber)
                <tr data-id="{{ $subscriber['id'] ?? $subscriber->id }}">
                    <td><input type="checkbox" class="subscriber-checkbox" value="{{ $subscriber['id'] ?? $subscriber->id }}"></td>
                    <td>{{ $subscriber['email'] ?? $subscriber->email }}</td>
                    <td>{{ isset($subscriber['created_at']) ? \Carbon\Carbon::parse($subscriber['created_at'])->format('d/m/Y H:i') : ($subscriber->created_at->format('d/m/Y H:i') ?? 'N/A') }}</td>
                    <td>
                        @php
                            $status = $subscriber['status'] ?? ($subscriber->status ?? 'active');
                        @endphp
                        @if($status === 'active')
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteSubscriber({{ $subscriber['id'] ?? $subscriber->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun abonné pour le moment</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination - CORRIGÉE -->
    @if(isset($stats['subscribers_pagination']) && $stats['subscribers_pagination'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="pagination-modern mt-4">
            {{ $stats['subscribers_pagination']->links() }}
        </div>
    @elseif(isset($stats['subscribers']) && method_exists($stats['subscribers'], 'links'))
        <div class="pagination-modern mt-4">
            {{ $stats['subscribers']->links() }}
        </div>
    @endif
</div>

<script>
function deleteSubscriber(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')) {
        showLoading();
        
        fetch(`/admin/cms/${currentEtablissementId}/subscribers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showToast('Abonné supprimé avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showToast('Erreur lors de la suppression', 'error');
        });
    }
}

// Select all checkbox
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('.subscriber-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }
    
    // Export subscribers
    const exportBtn = document.getElementById('exportSubscribersBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            window.location.href = `/admin/cms/${currentEtablissementId}/subscribers/export`;
        });
    }
    
    // Import subscribers
    const importBtn = document.getElementById('importSubscribersBtn');
    if (importBtn) {
        importBtn.addEventListener('click', function() {
            // Créer un input file caché
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.csv,.json';
            fileInput.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    importSubscribers(file);
                }
            };
            fileInput.click();
        });
    }
});

function importSubscribers(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    showLoading();
    
    fetch(`/admin/cms/${currentEtablissementId}/subscribers/import`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Erreur lors de l\'import', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showToast('Erreur lors de l\'import', 'error');
    });
}
</script>
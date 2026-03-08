@extends('layouts.app')

@section('content')
    <main class="dashboard-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-user me-2"></i>
                Détails de l'Utilisateur
            </h1>
            <div class="page-actions">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card user-profile-card">
                    <div class="card-body text-center">
                        <div class="user-avatar-large">
                            <i class="fas fa-user fa-4x"></i>
                        </div>
                        <h3 class="mt-3">{{ $user->name }}</h3>
                        <p class="text-muted">{{ $user->email }}</p>
                        
                        <div class="mt-3">
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>ID</span>
                                <span class="text-muted">#{{ $user->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Créé le</span>
                                <span class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Dernière modification</span>
                                <span class="text-muted">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </li>
                            @if($user->etablissement)
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Établissement</span>
                                <span class="text-muted">{{ $user->etablissement->name }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Rôles attribués</h5>
                        <button class="btn btn-sm btn-primary" onclick="openRolesModal({{ $user->id }})">
                            <i class="fas fa-user-shield me-1"></i>Gérer les rôles
                        </button>
                    </div>
                    <div class="card-body">
                        @if($user->roles->count() > 0)
                            <div class="roles-container">
                                @foreach($user->roles as $role)
                                    <span class="role-badge-large">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">Aucun rôle attribué</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $permissions = $user->getAllPermissions();
                        @endphp
                        
                        @if($permissions->count() > 0)
                            <div class="row">
                                @foreach($permissions->groupBy(function($item) {
                                    return explode('-', $item->name)[0] ?? 'other';
                                }) as $group => $groupPermissions)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-uppercase text-muted">{{ ucfirst($group) }}</h6>
                                        <ul class="list-unstyled">
                                            @foreach($groupPermissions as $permission)
                                                <li class="mb-1">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    {{ $permission->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">Aucune permission directe</p>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="openEditModal({{ $user->id }})">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </button>
                            
                            <button class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                    onclick="toggleUserStatus({{ $user->id }})">
                                <i class="fas fa-power-off me-2"></i>
                                {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                            
                            @if($user->id !== auth()->id())
                                <button class="btn btn-outline-danger" onclick="showDeleteConfirmation({{ $user->id }})">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include modals -->
    @include('users.edit-modal')
    @include('users.delete-modal')
    @include('users.roles-modal')

    <style>
        .user-profile-card {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .user-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }
        
        .role-badge-large {
            display: inline-block;
            padding: 8px 15px;
            margin: 5px;
            border-radius: 20px;
            background: linear-gradient(135deg, #9d4edd, #7b2cbf);
            color: white;
            font-weight: 500;
        }
        
        .roles-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
    </style>

    <script>
        // Reuse functions from the main page
        function openEditModal(userId) {
            // Fetch user data and populate modal
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('editUserId').value = user.id;
                        document.getElementById('editUserName').value = user.name;
                        document.getElementById('editUserEmail').value = user.email;
                        document.getElementById('editUserIsActive').checked = user.is_active;
                        
                        new bootstrap.Modal(document.getElementById('editUserModal')).show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Erreur lors du chargement des données');
                });
        }

        function openRolesModal(userId) {
            // Similar to main page function
            fetch('/api/roles')
                .then(response => response.json())
                .then(roles => {
                    const user = @json($user);
                    const rolesContainer = document.getElementById('rolesContainer');
                    rolesContainer.innerHTML = '';
                    
                    roles.forEach(role => {
                        const isAssigned = user.roles.some(r => r.id === role.id);
                        rolesContainer.innerHTML += `
                            <div class="form-check">
                                <input class="form-check-input role-checkbox" type="checkbox" 
                                       value="${role.id}" id="role-${role.id}" 
                                       ${isAssigned ? 'checked' : ''}>
                                <label class="form-check-label" for="role-${role.id}">
                                    ${role.name}
                                </label>
                            </div>
                        `;
                    });
                    
                    document.getElementById('userRolesId').value = user.id;
                    document.getElementById('userRolesName').textContent = user.name;
                    
                    new bootstrap.Modal(document.getElementById('rolesModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'Erreur lors du chargement des rôles');
                });
        }

        function toggleUserStatus(userId) {
            if (!confirm('Êtes-vous sûr de vouloir changer le statut de cet utilisateur ?')) {
                return;
            }
            
            fetch(`/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    // Reload page to update status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors du changement de statut');
            });
        }

        function showDeleteConfirmation(userId) {
            const user = @json($user);
            userToDelete = user;
            
            document.getElementById('userToDeleteInfo').innerHTML = `
                <div class="user-info">
                    <div class="user-info-avatar">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                    <div>
                        <div class="user-info-name">${user.name}</div>
                        <div class="user-info-email">${user.email}</div>
                        <div class="user-info-roles">Rôles: ${user.roles.map(r => r.name).join(', ') || 'Aucun'}</div>
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
        }

        function deleteUser() {
            const userId = {{ $user->id }};
            
            fetch(`/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => {
                        window.location.href = '{{ route("users.index") }}';
                    }, 1500);
                } else {
                    showAlert('danger', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Erreur lors de la suppression');
            });
        }

        function showAlert(type, message) {
            const existingAlert = document.querySelector('.alert-custom');
            if (existingAlert) existingAlert.remove();
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-custom alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 5000);
        }

        // Attach event listeners when modals are shown
        document.addEventListener('DOMContentLoaded', function() {
            // Update roles button
            const updateRolesBtn = document.getElementById('updateRolesBtn');
            if (updateRolesBtn) {
                updateRolesBtn.addEventListener('click', function() {
                    const userId = document.getElementById('userRolesId').value;
                    const selectedRoles = Array.from(document.querySelectorAll('.role-checkbox:checked'))
                        .map(cb => cb.value);
                    
                    fetch(`/users/${userId}/update-roles`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ roles: selectedRoles })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            const modal = bootstrap.Modal.getInstance(document.getElementById('rolesModal'));
                            modal.hide();
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showAlert('danger', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'Erreur lors de la mise à jour des rôles');
                    });
                });
            }
            
            // Confirm delete button
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', deleteUser);
            }
        });
    </script>
@endsection
@extends('layouts.app')

@section('content')
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-plus-circle"></i></span>
                Créer une nouvelle facture
            </h1>
            
            <div class="page-actions">
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <button type="button" class="btn btn-outline-primary" id="saveDraftBtn">
                    <i class="fas fa-save me-2"></i>Brouillon
                </button>
            </div>
        </div>

        <!-- Main Form -->
        <form id="invoiceForm">
            @csrf
            
            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Client & Project Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-user me-2"></i>Client et projet
                            </h3>
                        </div>
                        
                        <div class="card-body-modern">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern required">Client</label>
                                        <select class="form-select-modern" name="client_id" id="client_id" required>
                                            <option value="">Sélectionner un client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" data-client="{{ json_encode($client) }}">
                                                    {{ $client->nom_complet }}
                                                    @if($client->entreprise_nom) ({{ $client->entreprise_nom }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label-modern">Projet associé</label>
                                        <select class="form-select-modern" name="project_id" id="project_id">
                                            <option value="">Aucun projet</option>
                                            @foreach($projects as $project)
                                                <option value="{{ $project->id }}">{{ $project->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Client Info Preview -->
                            <div id="clientInfoPreview" class="client-preview" style="display: none;">
                                <h5>Informations client</h5>
                                <p id="clientAddress"></p>
                                <p id="clientVat"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Lines Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-list me-2"></i>Lignes de facture
                            </h3>
                            <button type="button" class="btn btn-sm btn-primary" id="addLineBtn">
                                <i class="fas fa-plus me-1"></i>Ajouter une ligne
                            </button>
                        </div>
                        
                        <div class="card-body-modern">
                            <div id="lines-container">
                                <!-- Lines will be added here -->
                            </div>
                            
                            <div class="line-template d-none">
                                <div class="invoice-line card mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="mb-2">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" name="lines[INDEX][description]" 
                                                           placeholder="Description" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <label class="form-label">Quantité</label>
                                                    <input type="number" class="form-control quantity" 
                                                           name="lines[INDEX][quantity]" value="1" min="0" step="0.01" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <label class="form-label">Prix unitaire</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control unit-price" 
                                                               name="lines[INDEX][unit_price]" value="0" min="0" step="0.01" required>
                                                        <span class="input-group-text">€</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-2">
                                                    <label class="form-label">TVA %</label>
                                                    <select class="form-select tax-rate" name="lines[INDEX][tax_rate]">
                                                        @foreach($taxes as $tax)
                                                            <option value="{{ $tax->rate }}"> {{ $tax->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="mb-2">
                                                    <label class="form-label">&nbsp;</label>
                                                    <button type="button" class="btn btn-danger btn-sm remove-line">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-end">
                                                <small class="line-total">Total: 0,00 €</small>
                                            </div>
                                        </div>
                                        <input type="hidden" name="lines[INDEX][product_id]" class="product-id">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Dates Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-calendar me-2"></i>Dates
                            </h3>
                        </div>
                        
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern required">Date de facture</label>
                                <input type="date" class="form-control-modern" name="invoice_date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-modern required">Date d'échéance</label>
                                <input type="date" class="form-control-modern" name="due_date" 
                                       value="{{ date('Y-m-d', strtotime('+' . ($defaultDueDays ?? 30) . ' days')) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-calculator me-2"></i>Récapitulatif
                            </h3>
                        </div>
                        
                        <div class="card-body-modern">
                            <div class="summary-item">
                                <span>Sous-total HT</span>
                                <span id="subtotal">0,00 €</span>
                            </div>
                            
                            <div class="summary-item">
                                <span>Total TVA</span>
                                <span id="taxTotal">0,00 €</span>
                            </div>
                            
                            <div class="summary-item total">
                                <span>Total TTC</span>
                                <span id="total">0,00 €</span>
                            </div>
                        </div>
                    </div>

                    <!-- Options Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-sliders-h me-2"></i>Options
                            </h3>
                        </div>
                        
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern">Remise (%)</label>
                                <input type="number" class="form-control-modern" name="discount_percentage" 
                                       id="discount" value="0" min="0" max="100" step="0.1">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-modern">Frais de livraison</label>
                                <div class="input-group">
                                    <input type="number" class="form-control-modern" name="shipping_fees" 
                                           id="shipping" value="0" min="0" step="0.01">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-modern">Frais d'administration</label>
                                <div class="input-group">
                                    <input type="number" class="form-control-modern" name="administration_fees" 
                                           id="adminFees" value="0" min="0" step="0.01">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-modern">Statut</label>
                                <select class="form-select-modern" name="status" id="status">
                                    <option value="brouillon">Brouillon</option>
                                    <option value="envoyee">Envoyer immédiatement</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Card -->
                    <div class="main-card-modern mb-4">
                        <div class="card-header-modern">
                            <h3 class="card-title-modern">
                                <i class="fas fa-sticky-note me-2"></i>Notes
                            </h3>
                        </div>
                        
                        <div class="card-body-modern">
                            <div class="mb-3">
                                <label class="form-label-modern">Notes internes</label>
                                <textarea class="form-control-modern" name="notes" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-modern">Pied de page</label>
                                <textarea class="form-control-modern" name="footer" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-check-circle me-2"></i>Créer la facture
                </button>
            </div>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lineIndex = 0;
            
            // Add first line
            addLine();

            // Client selection
            document.getElementById('client_id').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected.value) {
                    const client = JSON.parse(selected.dataset.client);
                    showClientInfo(client);
                } else {
                    document.getElementById('clientInfoPreview').style.display = 'none';
                }
            });

            // Add line button
            document.getElementById('addLineBtn').addEventListener('click', addLine);

            // Calculate totals on input change
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || 
                    e.target.classList.contains('unit-price') || 
                    e.target.classList.contains('tax-rate') ||
                    e.target.id === 'discount' ||
                    e.target.id === 'shipping' ||
                    e.target.id === 'adminFees') {
                    calculateTotals();
                }
            });

            // Form submission
            document.getElementById('invoiceForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) return;
                
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Création...';
                submitBtn.disabled = true;
                
                const formData = new FormData(this);
                
                $.ajax({
                    url: '{{ route("invoices.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', 'Facture créée avec succès !');
                            setTimeout(() => {
                                window.location.href = `/invoices/${response.data.id}`;
                            }, 1500);
                        } else {
                            showAlert('danger', response.message);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Erreur lors de la création';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        showAlert('danger', message);
                    },
                    complete: function() {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            });

            // Save draft
            document.getElementById('saveDraftBtn').addEventListener('click', function() {
                document.getElementById('status').value = 'brouillon';
                document.getElementById('invoiceForm').dispatchEvent(new Event('submit'));
            });

            function addLine() {
                const container = document.getElementById('lines-container');
                const template = document.querySelector('.line-template').cloneNode(true);
                template.classList.remove('line-template', 'd-none');
                
                const html = template.innerHTML.replace(/INDEX/g, lineIndex);
                const div = document.createElement('div');
                div.innerHTML = html;
                
                const line = div.firstElementChild;
                line.querySelector('.remove-line').addEventListener('click', function() {
                    line.remove();
                    calculateTotals();
                });
                
                container.appendChild(line);
                lineIndex++;
            }

            function showClientInfo(client) {
                const preview = document.getElementById('clientInfoPreview');
                const address = document.getElementById('clientAddress');
                const vat = document.getElementById('clientVat');
                
                let addressHtml = '';
                if (client.adresse) addressHtml += client.adresse + '<br>';
                if (client.code_postal || client.ville) {
                    addressHtml += (client.code_postal || '') + ' ' + (client.ville || '');
                }
                
                address.innerHTML = addressHtml;
                vat.innerHTML = client.no_tva ? `TVA: ${client.no_tva}` : '';
                
                preview.style.display = 'block';
            }

            function calculateTotals() {
                let subtotal = 0;
                let taxTotal = 0;
                
                document.querySelectorAll('.invoice-line').forEach(line => {
                    const quantity = parseFloat(line.querySelector('.quantity').value) || 0;
                    const unitPrice = parseFloat(line.querySelector('.unit-price').value) || 0;
                    const taxRate = parseFloat(line.querySelector('.tax-rate').value) || 0;
                    
                    const lineSubtotal = quantity * unitPrice;
                    const lineTax = lineSubtotal * (taxRate / 100);
                    
                    subtotal += lineSubtotal;
                    taxTotal += lineTax;
                    
                    const lineTotal = lineSubtotal + lineTax;
                    line.querySelector('.line-total').textContent = `Total: ${formatCurrency(lineTotal)}`;
                });
                
                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const shipping = parseFloat(document.getElementById('shipping').value) || 0;
                const adminFees = parseFloat(document.getElementById('adminFees').value) || 0;
                
                const discountAmount = subtotal * (discount / 100);
                const totalAfterDiscount = subtotal - discountAmount;
                const totalWithFees = totalAfterDiscount + shipping + adminFees;
                const total = totalWithFees + taxTotal;
                
                document.getElementById('subtotal').textContent = formatCurrency(subtotal);
                document.getElementById('taxTotal').textContent = formatCurrency(taxTotal);
                document.getElementById('total').textContent = formatCurrency(total);
            }

            function validateForm() {
                if (!document.getElementById('client_id').value) {
                    showAlert('danger', 'Veuillez sélectionner un client');
                    return false;
                }
                
                if (document.querySelectorAll('.invoice-line').length === 0) {
                    showAlert('danger', 'Ajoutez au moins une ligne de facture');
                    return false;
                }
                
                return true;
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('fr-FR', { 
                    style: 'currency', 
                    currency: 'EUR' 
                }).format(amount);
            }

            function showAlert(type, message) {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
                alert.style.zIndex = '9999';
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 5000);
            }
        });
    </script>

    <style>
        .client-preview {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .client-preview h5 {
            margin-bottom: 10px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .client-preview p {
            margin: 5px 0;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #dee2e6;
        }
        
        .summary-item.total {
            font-weight: bold;
            font-size: 1.2rem;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 12px 0;
            margin-top: 10px;
        }
        
        .line-total {
            font-weight: 500;
            color: #06b48a;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
@endsection
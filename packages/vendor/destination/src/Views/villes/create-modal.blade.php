<!-- CREATE VILLE MODAL -->
<div class="modal fade" id="createVilleModal" tabindex="-1" aria-labelledby="createVilleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="createVilleModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Créer une nouvelle ville
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="createVilleForm">
                    @csrf
                    
                    <!-- Section: Informations de base -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="villeName" class="form-label-modern">Nom de la ville *</label>
                                <input type="text" class="form-control-modern" id="villeName" name="name" 
                                       placeholder="Ex: Montréal, Québec, Sherbrooke..." required>
                                <div class="form-text-modern">Nom complet de la ville</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="villeCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="villeCode" name="code" 
                                       placeholder="Ex: MTL, QUE, SHE" maxlength="20">
                                <div class="form-text-modern">Code unique de la ville</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="villeClassification" class="form-label-modern">Classification</label>
                                <select class="form-select-modern" id="villeClassification" name="classification">
                                    <option value="">Sélectionner</option>
                                    <option value="Urbaine">Urbaine</option>
                                    <option value="Rurale">Rurale</option>
                                    <option value="Périurbaine">Périurbaine</option>
                                    <option value="Métropolitaine">Métropolitaine</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="villeStatus" class="form-label-modern">Statut</label>
                                <select class="form-select-modern" id="villeStatus" name="status">
                                    <option value="">Sélectionner</option>
                                    <option value="Capitale">Capitale</option>
                                    <option value="Capitale provinciale">Capitale provinciale</option>
                                    <option value="Capitale régionale">Capitale régionale</option>
                                    <option value="Métropole">Métropole</option>
                                    <option value="Ville">Ville</option>
                                    <option value="Municipalité">Municipalité</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villeMayor" class="form-label-modern">Maire</label>
                                <input type="text" class="form-control-modern" id="villeMayor" name="mayor" 
                                       placeholder="Ex: Valérie Plante">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villeWebsite" class="form-label-modern">Site web</label>
                                <input type="url" class="form-control-modern" id="villeWebsite" name="website" 
                                       placeholder="Ex: https://montreal.ca">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section: Localisation -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-map-marker-alt me-2"></i>Localisation
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="villeCountry" class="form-label-modern">Pays *</label>
                                <select class="form-select-modern" id="villeCountry" name="country_id" required 
                                        data-target="#villeProvince" data-url="{{ route('villes.provinces-by-country', '') }}">
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villeProvince" class="form-label-modern">Province *</label>
                                <select class="form-select-modern" id="villeProvince" name="province_id" required 
                                        data-target="#villeRegion" data-url="{{ route('villes.regions-by-province', '') }}">
                                    <option value="">Sélectionnez d'abord un pays</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villeRegion" class="form-label-modern">Région</label>
                                <select class="form-select-modern" id="villeRegion" name="region_id" 
                                        data-target="#villeSecteur" data-url="{{ route('villes.secteurs-by-region', '') }}">
                                    <option value="">Sélectionnez d'abord une province</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="villeSecteur" class="form-label-modern">Secteur</label>
                                <select class="form-select-modern" id="villeSecteur" name="secteur_id">
                                    <option value="">Sélectionnez d'abord une région</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villePostalCodePrefix" class="form-label-modern">Préfixe postal</label>
                                <input type="text" class="form-control-modern" id="villePostalCodePrefix" name="postal_code_prefix" 
                                       placeholder="Ex: H1A, G1A">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="villeFoundingYear" class="form-label-modern">Année de fondation</label>
                                <input type="number" class="form-control-modern" id="villeFoundingYear" name="founding_year" 
                                       min="1000" max="{{ date('Y') }}" placeholder="Ex: 1642">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section: Démographie et géographie -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-chart-bar me-2"></i>Démographie et géographie
                        </h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="villePopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="villePopulation" 
                                       name="population" placeholder="Ex: 1780000" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="villeArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="villeArea" name="area" 
                                       placeholder="Ex: 431.5" min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="villeHouseholds" class="form-label-modern">Ménages</label>
                                <input type="number" class="form-control-modern" id="villeHouseholds" name="households" 
                                       placeholder="Ex: 850000" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="villeAltitude" class="form-label-modern">Altitude (m)</label>
                                <input type="number" class="form-control-modern" id="villeAltitude" name="altitude" 
                                       placeholder="Ex: 57">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="villeLatitude" class="form-label-modern">Latitude</label>
                                <input type="number" class="form-control-modern" id="villeLatitude" name="latitude" 
                                       placeholder="Ex: 45.5017" step="any" min="-90" max="90">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="villeLongitude" class="form-label-modern">Longitude</label>
                                <input type="number" class="form-control-modern" id="villeLongitude" name="longitude" 
                                       placeholder="Ex: -73.5673" step="any" min="-180" max="180">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section: Description -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-file-alt me-2"></i>Description
                        </h6>
                        <div class="mb-3">
                            <label for="villeDescription" class="form-label-modern">Description générale</label>
                            <textarea class="form-control-modern" id="villeDescription" name="description" 
                                      rows="3" placeholder="Description générale de la ville..."></textarea>
                        </div>
                    </div>
                    
                    <!-- Section: Détails supplémentaires -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-layer-group me-2"></i>Détails supplémentaires
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="villeHistory" class="form-label-modern">Histoire</label>
                                <textarea class="form-control-modern" id="villeHistory" name="history" 
                                          rows="3" placeholder="Histoire de la ville..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="villeEconomy" class="form-label-modern">Économie</label>
                                <textarea class="form-control-modern" id="villeEconomy" name="economy" 
                                          rows="3" placeholder="Secteurs économiques..."></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="villeAttractions" class="form-label-modern">Attractions</label>
                                <textarea class="form-control-modern" id="villeAttractions" name="attractions" 
                                          rows="3" placeholder="Attractions touristiques..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="villeCulture" class="form-label-modern">Culture</label>
                                <textarea class="form-control-modern" id="villeCulture" name="culture" 
                                          rows="3" placeholder="Vie culturelle..."></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="villeTransport" class="form-label-modern">Transport</label>
                                <textarea class="form-control-modern" id="villeTransport" name="transport" 
                                          rows="2" placeholder="Infrastructures de transport..."></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="villeEducation" class="form-label-modern">Éducation</label>
                                <textarea class="form-control-modern" id="villeEducation" name="education" 
                                          rows="2" placeholder="Institutions éducatives..."></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary btn-pulse" id="submitVilleBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Créer la ville
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
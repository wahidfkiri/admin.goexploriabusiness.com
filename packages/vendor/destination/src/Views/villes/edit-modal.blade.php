<!-- EDIT VILLE MODAL -->
<div class="modal fade" id="editVilleModal" tabindex="-1" aria-labelledby="editVilleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content modal-content-modern">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern" id="editVilleModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier la ville
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-modern">
                <form id="editVilleForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editVilleId" name="id">
                    
                    <!-- Section: Informations de base -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-info-circle me-2"></i>Informations de base
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editVilleName" class="form-label-modern">Nom de la ville *</label>
                                <input type="text" class="form-control-modern" id="editVilleName" name="name" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editVilleCode" class="form-label-modern">Code</label>
                                <input type="text" class="form-control-modern" id="editVilleCode" name="code" maxlength="20">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editVilleClassification" class="form-label-modern">Classification</label>
                                <select class="form-select-modern" id="editVilleClassification" name="classification">
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
                                <label for="editVilleStatus" class="form-label-modern">Statut</label>
                                <select class="form-select-modern" id="editVilleStatus" name="status">
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
                                <label for="editVilleMayor" class="form-label-modern">Maire</label>
                                <input type="text" class="form-control-modern" id="editVilleMayor" name="mayor">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editVilleWebsite" class="form-label-modern">Site web</label>
                                <input type="url" class="form-control-modern" id="editVilleWebsite" name="website">
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
                                <label for="editVilleCountry" class="form-label-modern">Pays *</label>
                                <select class="form-select-modern" id="editVilleCountry" name="country_id" required 
                                        data-target="#editVilleProvince" data-url="{{ route('villes.provinces-by-country', '') }}">
                                    <option value="">Sélectionnez un pays</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editVilleProvince" class="form-label-modern">Province *</label>
                                <select class="form-select-modern" id="editVilleProvince" name="province_id" required 
                                        data-target="#editVilleRegion" data-url="{{ route('villes.regions-by-province', '') }}">
                                    <option value="">Sélectionnez un pays d'abord</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editVilleRegion" class="form-label-modern">Région</label>
                                <select class="form-select-modern" id="editVilleRegion" name="region_id" 
                                        data-target="#editVilleSecteur" data-url="{{ route('villes.secteurs-by-region', '') }}">
                                    <option value="">Sélectionnez une province d'abord</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editVilleSecteur" class="form-label-modern">Secteur</label>
                                <select class="form-select-modern" id="editVilleSecteur" name="secteur_id">
                                    <option value="">Sélectionnez une région d'abord</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editVillePostalCodePrefix" class="form-label-modern">Préfixe postal</label>
                                <input type="text" class="form-control-modern" id="editVillePostalCodePrefix" name="postal_code_prefix">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editVilleFoundingYear" class="form-label-modern">Année de fondation</label>
                                <input type="number" class="form-control-modern" id="editVilleFoundingYear" name="founding_year" 
                                       min="1000" max="{{ date('Y') }}">
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
                                <label for="editVillePopulation" class="form-label-modern">Population</label>
                                <input type="number" class="form-control-modern" id="editVillePopulation" name="population" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editVilleArea" class="form-label-modern">Superficie (km²)</label>
                                <input type="number" class="form-control-modern" id="editVilleArea" name="area" min="0" step="0.01">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editVilleHouseholds" class="form-label-modern">Ménages</label>
                                <input type="number" class="form-control-modern" id="editVilleHouseholds" name="households" min="0">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editVilleAltitude" class="form-label-modern">Altitude (m)</label>
                                <input type="number" class="form-control-modern" id="editVilleAltitude" name="altitude">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editVilleLatitude" class="form-label-modern">Latitude</label>
                                <input type="number" class="form-control-modern" id="editVilleLatitude" name="latitude" 
                                       step="any" min="-90" max="90">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editVilleLongitude" class="form-label-modern">Longitude</label>
                                <input type="number" class="form-control-modern" id="editVilleLongitude" name="longitude" 
                                       step="any" min="-180" max="180">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section: Description -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-file-alt me-2"></i>Description
                        </h6>
                        <div class="mb-3">
                            <label for="editVilleDescription" class="form-label-modern">Description générale</label>
                            <textarea class="form-control-modern" id="editVilleDescription" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    
                    <!-- Section: Détails supplémentaires -->
                    <div class="section-modern mb-4">
                        <h6 class="section-title-modern">
                            <i class="fas fa-layer-group me-2"></i>Détails supplémentaires
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editVilleHistory" class="form-label-modern">Histoire</label>
                                <textarea class="form-control-modern" id="editVilleHistory" name="history" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editVilleEconomy" class="form-label-modern">Économie</label>
                                <textarea class="form-control-modern" id="editVilleEconomy" name="economy" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editVilleAttractions" class="form-label-modern">Attractions</label>
                                <textarea class="form-control-modern" id="editVilleAttractions" name="attractions" rows="3"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editVilleCulture" class="form-label-modern">Culture</label>
                                <textarea class="form-control-modern" id="editVilleCulture" name="culture" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editVilleTransport" class="form-label-modern">Transport</label>
                                <textarea class="form-control-modern" id="editVilleTransport" name="transport" rows="2"></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editVilleEducation" class="form-label-modern">Éducation</label>
                                <textarea class="form-control-modern" id="editVilleEducation" name="education" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modal-footer-modern">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="updateVilleBtn">
                    <span class="btn-text">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
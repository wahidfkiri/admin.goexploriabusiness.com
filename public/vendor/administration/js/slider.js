    // Configuration
    let currentPage = 1;
    let currentFilters = {};
    let allSliders = [];
    let orderSliders = [];
    let sliderToDelete = null;
    let sortable = null;
    let originalOrder = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        setupAjax();
        loadSliders();
        loadStatistics();
        loadLocations();
        setupEventListeners();
        setupImagePreview();
        setupVideoTypeToggle();
        setupLocationSearch();
        setupHierarchicalSelects();
        setupVideoSourceToggle();
        setupVideoUrlPreview();
        setupVideoFilePreview();
        setupVideoPlatformToggle();
    });

    // AJAX setup
    const setupAjax = () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    };

    // ==================== LOCALISATION (HIÉRARCHIQUE) ====================
    const loadLocations = () => {
        $.ajax({
            url: '/api/locations/countries',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    populateCountrySelects(response.data);
                }
            },
            error: function() {
                console.error('Erreur lors du chargement des pays');
            }
        });
    };

    const populateCountrySelects = (countries) => {
        const selects = ['sliderCountry', 'editSliderCountry', 'filterCountry'];
        selects.forEach(selectId => {
            const select = document.getElementById(selectId);
            if (select) {
                select.innerHTML = '<option value="">Sélectionnez un pays...</option>';
                countries.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.id;
                    option.textContent = country.name;
                    select.appendChild(option);
                });
            }
        });
    };

    const setupHierarchicalSelects = () => {
        // Création modal
        const countrySelect = document.getElementById('sliderCountry');
        const provinceSelect = document.getElementById('sliderProvince');
        const regionSelect = document.getElementById('sliderRegion');
        const villeSelect = document.getElementById('sliderVille');

        if (countrySelect) {
            countrySelect.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvinces(countryId, provinceSelect);
                    resetSelect(provinceSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(provinceSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                }
            });
        }

        if (provinceSelect) {
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegions(provinceId, regionSelect);
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(regionSelect);
                    resetSelect(villeSelect);
                }
            });
        }

        if (regionSelect) {
            regionSelect.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVilles(regionId, villeSelect);
                    resetSelect(villeSelect);
                } else {
                    resetSelect(villeSelect);
                }
            });
        }

        // Édition modal
        const editCountrySelect = document.getElementById('editSliderCountry');
        const editProvinceSelect = document.getElementById('editSliderProvince');
        const editRegionSelect = document.getElementById('editSliderRegion');
        const editVilleSelect = document.getElementById('editSliderVille');

        if (editCountrySelect) {
            editCountrySelect.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvinces(countryId, editProvinceSelect);
                    resetSelect(editProvinceSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editProvinceSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                }
            });
        }

        if (editProvinceSelect) {
            editProvinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegions(provinceId, editRegionSelect);
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editRegionSelect);
                    resetSelect(editVilleSelect);
                }
            });
        }

        if (editRegionSelect) {
            editRegionSelect.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVilles(regionId, editVilleSelect);
                    resetSelect(editVilleSelect);
                } else {
                    resetSelect(editVilleSelect);
                }
            });
        }

        // Filtres
        const filterCountry = document.getElementById('filterCountry');
        const filterProvince = document.getElementById('filterProvince');
        const filterRegion = document.getElementById('filterRegion');
        const filterVille = document.getElementById('filterVille');

        if (filterCountry) {
            filterCountry.addEventListener('change', function() {
                const countryId = this.value;
                if (countryId) {
                    loadProvincesForFilter(countryId, filterProvince);
                    resetSelect(filterProvince);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterProvince);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                }
            });
        }

        if (filterProvince) {
            filterProvince.addEventListener('change', function() {
                const provinceId = this.value;
                if (provinceId) {
                    loadRegionsForFilter(provinceId, filterRegion);
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterRegion);
                    resetSelect(filterVille);
                }
            });
        }

        if (filterRegion) {
            filterRegion.addEventListener('change', function() {
                const regionId = this.value;
                if (regionId) {
                    loadVillesForFilter(regionId, filterVille);
                    resetSelect(filterVille);
                } else {
                    resetSelect(filterVille);
                }
            });
        }
    };

    const loadProvinces = (countryId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/countries/${countryId}/provinces`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une province...</option>';
                    response.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadRegions = (provinceId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/provinces/${provinceId}/regions`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une région...</option>';
                    response.data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadVilles = (regionId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/regions/${regionId}/villes`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Sélectionnez une ville...</option>';
                    response.data.forEach(ville => {
                        const option = document.createElement('option');
                        option.value = ville.id;
                        option.textContent = ville.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadProvincesForFilter = (countryId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/countries/${countryId}/provinces`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les provinces</option>';
                    response.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadRegionsForFilter = (provinceId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/provinces/${provinceId}/regions`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les régions</option>';
                    response.data.forEach(region => {
                        const option = document.createElement('option');
                        option.value = region.id;
                        option.textContent = region.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const loadVillesForFilter = (regionId, selectElement) => {
        if (!selectElement) return;
        $.ajax({
            url: `/api/locations/regions/${regionId}/villes`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    selectElement.innerHTML = '<option value="">Toutes les villes</option>';
                    response.data.forEach(ville => {
                        const option = document.createElement('option');
                        option.value = ville.id;
                        option.textContent = ville.name;
                        selectElement.appendChild(option);
                    });
                    selectElement.disabled = false;
                }
            }
        });
    };

    const resetSelect = (selectElement) => {
        if (selectElement) {
            selectElement.innerHTML = '<option value="">Sélectionnez d\'abord...</option>';
            selectElement.disabled = true;
        }
    };

    const setupLocationSearch = () => {
        const searchInput = document.getElementById('locationSearchInput');
        if (!searchInput) return;

        let timeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const keyword = this.value;
            if (keyword.length < 2) {
                hideLocationResults();
                return;
            }
            timeout = setTimeout(() => searchLocation(keyword), 300);
        });
    };

    const searchLocation = (keyword) => {
        $.ajax({
            url: `/api/locations/search?q=${encodeURIComponent(keyword)}`,
            type: 'GET',
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    displayLocationResults(response.data);
                } else {
                    hideLocationResults();
                }
            }
        });
    };

    const displayLocationResults = (results) => {
        let resultsContainer = document.getElementById('locationSearchResults');
        if (!resultsContainer) {
            resultsContainer = document.createElement('div');
            resultsContainer.id = 'locationSearchResults';
            resultsContainer.className = 'location-search-results';
            const searchInput = document.getElementById('locationSearchInput');
            if (searchInput && searchInput.parentNode) {
                searchInput.parentNode.style.position = 'relative';
                searchInput.parentNode.appendChild(resultsContainer);
            }
        }

        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'block';

        results.forEach(result => {
            const item = document.createElement('div');
            item.className = 'location-result-item';
            item.innerHTML = `
                <div class="result-type-badge ${result.type}">${result.type_label}</div>
                <div class="result-name">${escapeHtml(result.name)}</div>
                <div class="result-hierarchy">${escapeHtml(result.hierarchy)}</div>
            `;
            item.addEventListener('click', () => selectLocation(result));
            resultsContainer.appendChild(item);
        });
    };

    const hideLocationResults = () => {
        const container = document.getElementById('locationSearchResults');
        if (container) container.style.display = 'none';
    };

    const selectLocation = (location) => {
        const createModal = document.getElementById('createSliderModal');
        const editModal = document.getElementById('editSliderModal');
        const isEditModal = editModal?.classList.contains('show');

        if (isEditModal) {
            switch(location.type) {
                case 'country':
                    document.getElementById('editSliderCountry').value = location.id;
                    document.getElementById('editSliderCountry').dispatchEvent(new Event('change'));
                    break;
                case 'province':
                    document.getElementById('editSliderProvince').value = location.id;
                    document.getElementById('editSliderProvince').dispatchEvent(new Event('change'));
                    break;
                case 'region':
                    document.getElementById('editSliderRegion').value = location.id;
                    document.getElementById('editSliderRegion').dispatchEvent(new Event('change'));
                    break;
                case 'ville':
                    document.getElementById('editSliderVille').value = location.id;
                    break;
            }
        } else {
            switch(location.type) {
                case 'country':
                    document.getElementById('sliderCountry').value = location.id;
                    document.getElementById('sliderCountry').dispatchEvent(new Event('change'));
                    break;
                case 'province':
                    document.getElementById('sliderProvince').value = location.id;
                    document.getElementById('sliderProvince').dispatchEvent(new Event('change'));
                    break;
                case 'region':
                    document.getElementById('sliderRegion').value = location.id;
                    document.getElementById('sliderRegion').dispatchEvent(new Event('change'));
                    break;
                case 'ville':
                    document.getElementById('sliderVille').value = location.id;
                    break;
            }
        }

        const searchInput = document.getElementById('locationSearchInput');
        if (searchInput) searchInput.value = location.hierarchy;
        hideLocationResults();
    };

    // Load sliders
    const loadSliders = (page = 1, filters = {}) => {
        showLoading();
        
        const searchTerm = document.getElementById('searchInput')?.value || '';
        
        $.ajax({
            url: '/sliders',
            type: 'GET',
            data: {
                page: page,
                search: searchTerm,
                ...filters,
                ajax: true
            },
            success: function(response) {
                if (response.success) {
                    allSliders = response.data || [];
                    renderSliders(allSliders);
                    renderPagination(response);
                    hideLoading();
                } else {
                    showError('Erreur lors du chargement des sliders');
                }
            },
            error: function(xhr) {
                hideLoading();
                showError('Erreur de connexion au serveur');
                console.error('Error:', xhr.responseText);
            }
        });
    };

    // Load statistics
    const loadStatistics = () => {
        $.ajax({
            url: '/sliders/statistics/data',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const stats = response.data;
                    document.getElementById('totalSliders').textContent = stats.total;
                    document.getElementById('activeSliders').textContent = stats.active;
                    document.getElementById('imageSliders').textContent = stats.images;
                    document.getElementById('videoSliders').textContent = stats.videos;
                }
            }
        });
    };

    // Render sliders
    const renderSliders = (sliders) => {
        const tbody = document.getElementById('slidersTableBody');
        tbody.innerHTML = '';
        
        if (!sliders || !Array.isArray(sliders) || sliders.length === 0) {
            document.getElementById('emptyState').style.display = 'block';
            document.getElementById('tableContainer').style.display = 'none';
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        sliders.forEach((slider, index) => {
            const row = document.createElement('tr');
            row.id = `slider-row-${slider.id}`;
            row.style.animationDelay = `${index * 0.05}s`;
            
            const createdDate = new Date(slider.created_at);
            const formattedDate = createdDate.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
            
            let typeClass = 'type-image-modern';
            let typeIcon = 'fa-image';
            let typeText = 'Image';
            
            if (slider.type === 'video') {
                typeClass = 'type-video-modern';
                typeIcon = 'fa-video';
                typeText = 'Vidéo';
                
                if (slider.video_type === 'youtube') {
                    typeText = 'YouTube';
                    typeIcon = 'fa-youtube';
                } else if (slider.video_type === 'vimeo') {
                    typeText = 'Vimeo';
                    typeIcon = 'fa-vimeo';
                } else if (slider.video_type === 'upload') {
                    typeText = 'Upload';
                    typeIcon = 'fa-upload';
                } else if (slider.video_type === 'other') {
                    typeText = 'Autre';
                    typeIcon = 'fa-link';
                }
            }
            
            let statusClass = 'status-active-modern';
            let statusText = 'Actif';
            let statusIcon = 'fa-check-circle';
            
            if (!slider.is_active) {
                statusClass = 'status-inactive-modern';
                statusText = 'Inactif';
                statusIcon = 'fa-ban';
            }
            
            let previewContent = '';
            let imageUrl = '';
            
            if (slider.type === 'image') {
                if (slider.image_path) {
                    if (slider.image_path.startsWith('http')) {
                        imageUrl = slider.image_path;
                    } else {
                        imageUrl = `/storage/${slider.image_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Image'; this.classList.add('placeholder-image');">`;
                } else {
                    previewContent = `<div class="slider-icon-placeholder"><i class="fas fa-image"></i></div>`;
                }
            } else if (slider.type === 'video') {
                if (slider.thumbnail_path) {
                    if (slider.thumbnail_path.startsWith('http')) {
                        imageUrl = slider.thumbnail_path;
                    } else {
                        imageUrl = `/storage/${slider.thumbnail_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Video'; this.classList.add('placeholder-image');">`;
                } else if (slider.image_path) {
                    if (slider.image_path.startsWith('http')) {
                        imageUrl = slider.image_path;
                    } else {
                        imageUrl = `/storage/${slider.image_path}`;
                    }
                    previewContent = `<img src="${imageUrl}" alt="${slider.name}" class="slider-thumbnail" onerror="this.onerror=null; this.src='https://via.placeholder.com/60x60?text=Video'; this.classList.add('placeholder-image');">`;
                } else {
                    previewContent = `<div class="slider-icon-placeholder video-placeholder"><i class="fas fa-video"></i></div>`;
                }
            }
            
            let fullLocation = slider.full_location || '';
            if (!fullLocation) {
                const parts = [];
                if (slider.country) parts.push(slider.country.name);
                if (slider.province) parts.push(slider.province.name);
                if (slider.region) parts.push(slider.region.name);
                if (slider.ville) parts.push(slider.ville.name);
                fullLocation = parts.join(' › ') || 'Non assigné';
            }
            
            const isFirstRow = index === 0;
            const isLastRow = index === (sliders.length - 1);
            let orderActionsHtml = '';

            if (isFirstRow) {
                orderActionsHtml = `<button class="table-order-btn flash-arrow" title="Descendre" onclick="moveOrderQuick(${slider.id}, 1)">&darr;</button>`;
            } else if (isLastRow) {
                orderActionsHtml = `<button class="table-order-btn flash-arrow" title="Monter" onclick="moveOrderQuick(${slider.id}, -1)">&uarr;</button>`;
            } else {
                orderActionsHtml = `
                    <button class="table-order-btn flash-arrow" title="Monter" onclick="moveOrderQuick(${slider.id}, -1)">&uarr;</button>
                    <button class="table-order-btn flash-arrow" title="Descendre" onclick="moveOrderQuick(${slider.id}, 1)">&darr;</button>
                `;
            }

            row.innerHTML = `
                <td style="width: 72px;">
                    <div class="table-order-actions">
                        ${orderActionsHtml}
                    </div>
                </td>
                <td class="slider-name-cell"><div class="slider-name-modern"><div class="slider-icon-modern">${previewContent}</div><div><div class="slider-name-text">${escapeHtml(slider.name)}</div><small class="text-muted">ID: ${slider.id}</small></div></div></td>
                <td><span class="slider-type-modern ${typeClass}"><i class="fab ${typeIcon} me-1"></i>${typeText}</span></td>
                <td><span class="slider-region-modern"><i class="fas fa-map-marker-alt me-1"></i>${escapeHtml(fullLocation)}</span></td>
                <td><span class="slider-status-modern ${statusClass}"><i class="fas ${statusIcon} me-1"></i>${statusText}</span></td>
                <td><div>${formattedDate}</div><small class="text-muted">${formatTimeAgo(createdDate)}</small></td>
                <td style="text-align: center;"><div class="slider-actions-modern">
                    <button class="action-btn-modern preview-btn-modern" title="Aperçu" onclick="previewSlider(${slider.id})"><i class="fas fa-eye"></i></button>
                    <button class="action-btn-modern edit-btn-modern" title="Modifier" onclick="openEditModal(${slider.id})"><i class="fas fa-edit"></i></button>
                    <button class="action-btn-modern status-btn-modern" title="Changer statut" onclick="toggleSliderStatus(${slider.id})"><i class="fas fa-power-off"></i></button>
                    <button class="action-btn-modern delete-btn-modern" title="Supprimer" onclick="showDeleteConfirmation(${slider.id})"><i class="fas fa-trash"></i></button>
                </div></td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('paginationContainer').style.display = 'flex';
    };

    const escapeHtml = (text) => {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    const setupSortable = (sliders) => {
        const sortableList = document.getElementById('sortableList');
        sortableList.innerHTML = '';
        
        const sortedSliders = [...sliders].sort((a, b) => a.order - b.order);
        originalOrder = sortedSliders.map(s => ({id: s.id, order: s.order}));
        
        sortedSliders.forEach(slider => {
            const item = document.createElement('div');
            item.className = 'sortable-item';
            item.dataset.id = slider.id;
            
            let typeIcon = 'fa-image';
            let typeText = 'Image';
            
            if (slider.type === 'video') {
                typeIcon = 'fa-video';
                typeText = 'Vidéo';
                if (slider.video_type === 'youtube') { typeIcon = 'fa-youtube'; typeText = 'YouTube'; }
                else if (slider.video_type === 'vimeo') { typeIcon = 'fa-vimeo'; typeText = 'Vimeo'; }
            }
            
            let imageUrl = 'https://via.placeholder.com/60';
            if (slider.thumbnail_path) {
                imageUrl = slider.thumbnail_path.startsWith('http') ? slider.thumbnail_path : `/storage/${slider.thumbnail_path}`;
            } else if (slider.image_path) {
                imageUrl = slider.image_path.startsWith('http') ? slider.image_path : `/storage/${slider.image_path}`;
            }
            
            item.innerHTML = `
                <div class="sortable-item-content">
                    <div class="sortable-actions" title="Déplacer">
                        <button type="button" class="order-action-btn move-up" title="Monter"><i class="fas fa-chevron-up"></i></button>
                        <button type="button" class="order-action-btn move-down" title="Descendre"><i class="fas fa-chevron-down"></i></button>
                    </div>
                    <div class="sortable-image"><img src="${imageUrl}" alt="${slider.name}"></div>
                    <div class="sortable-info">
                        <div class="sortable-name">${slider.name}</div>
                        <div class="sortable-details">
                            <span class="badge bg-secondary me-2"><i class="fas ${typeIcon} me-1"></i>${typeText}</span>
                            <span class="badge ${slider.is_active ? 'bg-success' : 'bg-secondary'}"><i class="fas ${slider.is_active ? 'fa-check' : 'fa-ban'} me-1"></i>${slider.is_active ? 'Actif' : 'Inactif'}</span>
                        </div>
                    </div>
                    <div class="sortable-order"><span class="order-badge">${slider.order}</span></div>
                </div>
            `;
            sortableList.appendChild(item);
        });

        sortableList.querySelectorAll('.move-up').forEach(btn => {
            btn.addEventListener('click', () => moveSortableItem(btn.closest('.sortable-item'), -1));
        });
        sortableList.querySelectorAll('.move-down').forEach(btn => {
            btn.addEventListener('click', () => moveSortableItem(btn.closest('.sortable-item'), +1));
        });

        updateOrderNumbers();
    };

    const updateOrderNumbers = () => {
        document.querySelectorAll('.sortable-item').forEach((item, index) => {
            item.querySelector('.order-badge').textContent = index + 1;
        });
    };

    const moveSortableItem = (item, direction) => {
        if (!item) return;
        const parent = item.parentElement;
        if (!parent) return;

        if (direction < 0) {
            const prev = item.previousElementSibling;
            if (prev) parent.insertBefore(item, prev);
        } else {
            const next = item.nextElementSibling;
            if (next) parent.insertBefore(next, item);
        }

        updateOrderNumbers();
    };

    async function moveOrderQuick(sliderId, direction) {
        try {
            const sliders = await loadAllSlidersForOrdering(currentFilters);
            const sorted = [...sliders].sort((a, b) => a.order - b.order);
            const idx = sorted.findIndex(s => parseInt(s.id) === parseInt(sliderId));
            if (idx === -1) return;

            const target = idx + direction;
            if (target < 0 || target >= sorted.length) return;

            const tmp = sorted[idx];
            sorted[idx] = sorted[target];
            sorted[target] = tmp;

            const payload = sorted.map((s, i) => ({ id: parseInt(s.id), order: i + 1 }));

            $.ajax({
                url: '/sliders/update-order',
                type: 'POST',
                data: { sliders: payload },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', 'Ordre mis a jour');
                        loadSliders(currentPage, currentFilters);
                        loadStatistics();
                    } else {
                        showAlert('danger', response.message || 'Erreur lors de la sauvegarde');
                    }
                },
                error: function() {
                    showAlert('danger', 'Erreur lors de la sauvegarde');
                }
            });
        } catch (e) {
            showAlert('danger', e.message || 'Erreur lors du chargement');
        }
    }
    window.moveOrderQuick = moveOrderQuick;

    const saveOrder = () => {
        const items = document.querySelectorAll('.sortable-item');
        const slidersData = [];
        items.forEach((item, index) => slidersData.push({ id: parseInt(item.dataset.id), order: index + 1 }));
        
        const saveBtn = document.getElementById('saveOrderBtn');
        const saveBtn2 = document.getElementById('saveOrderBtn2');
        const originalText = saveBtn.innerHTML;
        
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
        saveBtn2.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde...';
        saveBtn.disabled = true;
        saveBtn2.disabled = true;
        
        $.ajax({
            url: '/sliders/update-order',
            type: 'POST',
            data: { sliders: slidersData },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Ordre sauvegardé avec succès !');
                    loadSliders(currentPage, currentFilters);
                    loadStatistics();
                    toggleOrderView();
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la sauvegarde');
                }
            },
            error: () => showAlert('danger', 'Erreur lors de la sauvegarde'),
            complete: () => {
                saveBtn.innerHTML = originalText;
                saveBtn2.innerHTML = originalText;
                saveBtn.disabled = false;
                saveBtn2.disabled = false;
            }
        });
    };

    const loadAllSlidersForOrdering = (filters = {}) => {
        return new Promise((resolve, reject) => {
            const searchTerm = document.getElementById('searchInput')?.value || '';
            $.ajax({
                url: '/sliders',
                type: 'GET',
                data: {
                    page: 1,
                    per_page: 1000,
                    search: searchTerm,
                    ...filters,
                    ajax: true
                },
                success: function(response) {
                    if (response.success) {
                        resolve(response.data || []);
                    } else {
                        reject(new Error(response.message || 'Erreur lors du chargement des sliders'));
                    }
                },
                error: function() {
                    reject(new Error('Erreur de connexion au serveur'));
                }
            });
        });
    };

    const toggleOrderView = async () => {
        const tableView = document.getElementById('tableView');
        const orderContainer = document.getElementById('orderContainer');
        const toggleBtn = document.getElementById('toggleOrderView');
        const saveBtn = document.getElementById('saveOrderBtn');
        
        if (tableView.style.display === 'none') {
            tableView.style.display = 'block';
            orderContainer.style.display = 'none';
            saveBtn.style.display = 'none';
            toggleBtn.innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
        } else {
            const initialBtn = '<i class="fas fa-sort me-1"></i>Vue par ordre';
            toggleBtn.disabled = true;
            toggleBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Chargement...';
            try {
                orderSliders = await loadAllSlidersForOrdering(currentFilters);
                if (orderSliders.length === 0) {
                    showAlert('info', 'Aucun slider a reorganiser');
                    toggleBtn.innerHTML = initialBtn;
                    return;
                }
                tableView.style.display = 'none';
                orderContainer.style.display = 'block';
                saveBtn.style.display = 'inline-block';
                toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i>Vue tableau';
                setupSortable(orderSliders);
            } catch (error) {
                showAlert('danger', error.message || 'Erreur lors du chargement');
                toggleBtn.innerHTML = initialBtn;
            } finally {
                toggleBtn.disabled = false;
            }
        }
    };

    const cancelOrder = () => {
        document.getElementById('tableView').style.display = 'block';
        document.getElementById('orderContainer').style.display = 'none';
        document.getElementById('saveOrderBtn').style.display = 'none';
        document.getElementById('toggleOrderView').innerHTML = '<i class="fas fa-sort me-1"></i>Vue par ordre';
        orderSliders = [];
    };

    const previewSlider = (sliderId) => {
        $.ajax({
            url: `/sliders/${sliderId}/preview`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const slider = response.data;
                    const previewContent = document.getElementById('previewContent');
                    let content = '';
                    
                    if (slider.type === 'image') {
                        content = `
                            <div class="slider-preview text-center">
                                <h5>${escapeHtml(slider.name)}</h5>
                                <img src="${slider.image_url}" class="img-fluid rounded mb-3" style="max-height: 400px;">
                                ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                            </div>`;
                    } else if (slider.type === 'video') {
                        if (slider.is_youtube && slider.youtube_id) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="https://www.youtube.com/embed/${slider.youtube_id}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else if (slider.is_vimeo && slider.video_url) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <div class="ratio ratio-16x9 mb-3">
                                        <iframe src="${slider.video_url.replace('vimeo.com', 'player.vimeo.com/video')}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else if (slider.video_url) {
                            content = `
                                <div class="slider-preview text-center">
                                    <h5>${escapeHtml(slider.name)}</h5>
                                    <video controls style="width:100%; max-height:400px;" poster="${slider.thumbnail_url}">
                                        <source src="${slider.video_url}" type="video/mp4">
                                    </video>
                                    ${slider.description ? `<p>${escapeHtml(slider.description)}</p>` : ''}
                                    ${slider.button_text && slider.button_url ? `<a href="${slider.button_url}" class="btn btn-primary" target="_blank">${slider.button_text}</a>` : ''}
                                </div>`;
                        } else {
                            content = `<div class="slider-preview text-center"><h5>${escapeHtml(slider.name)}</h5><div class="preview-placeholder"><i class="fas fa-video fa-4x mb-3"></i><p>Vidéo non disponible</p></div></div>`;
                        }
                    }
                    previewContent.innerHTML = content;
                    new bootstrap.Modal(document.getElementById('previewModal')).show();
                }
            },
            error: () => showAlert('danger', 'Erreur lors du chargement de l\'aperçu')
        });
    };

    const showDeleteConfirmation = (sliderId) => {
        const slider = allSliders.find(s => s.id === sliderId);
        if (!slider) { showAlert('danger', 'Slider non trouvé'); return; }
        sliderToDelete = slider;
        
        const createdDate = new Date(slider.created_at);
        const formattedDate = createdDate.toLocaleDateString('fr-FR');
        
        document.getElementById('sliderToDeleteInfo').innerHTML = `
            <div class="slider-info"><div class="slider-info-icon"><i class="fas fa-sliders-h"></i></div>
            <div><div class="slider-info-name">${slider.name}</div><div class="slider-info-type"><span class="badge bg-secondary">${slider.type === 'image' ? 'Image' : 'Vidéo'}</span></div></div></div>
            <div class="row small text-muted"><div class="col-6"><strong>ID:</strong> ${slider.id}</div><div class="col-6"><strong>Ordre:</strong> ${slider.order}</div>
            <div class="col-6"><strong>Créé le:</strong> ${formattedDate}</div><div class="col-6"><strong>Statut:</strong> ${slider.is_active ? 'Actif' : 'Inactif'}</div></div>`;
        
        document.getElementById('confirmDeleteBtn').innerHTML = `<span class="btn-text"><i class="fas fa-trash me-2"></i>Supprimer définitivement</span>`;
        document.getElementById('confirmDeleteBtn').disabled = false;
        new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
    };

    const deleteSlider = () => {
        if (!sliderToDelete) return;
        const deleteBtn = document.getElementById('confirmDeleteBtn');
        deleteBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Suppression...';
        deleteBtn.disabled = true;
        
        $.ajax({
            url: `/sliders/${sliderToDelete.id}`,
            type: 'DELETE',
            success: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal')).hide();
                if (response.success) {
                    loadStatistics();
                    loadSliders(currentPage, currentFilters);
                    showAlert('success', response.message);
                }
            },
            error: () => showAlert('danger', 'Erreur lors de la suppression'),
            complete: () => { sliderToDelete = null; }
        });
    };

    const toggleSliderStatus = (sliderId) => {
        $.ajax({
            url: `/sliders/${sliderId}/toggle-status`,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    loadSliders(currentPage, currentFilters);
                    loadStatistics();
                    showAlert('success', response.message);
                }
            },
            error: () => showAlert('danger', 'Erreur lors du changement de statut')
        });
    };

    const closeModalSafely = (modalId) => {
    return new Promise((resolve) => {
        const modalElement = document.getElementById(modalId);
        if (!modalElement) {
            resolve();
            return;
        }
        
        // Récupérer l'instance Bootstrap
        let modal = bootstrap.Modal.getInstance(modalElement);
        
        if (!modal) {
            modal = new bootstrap.Modal(modalElement);
        }
        
        // Écouter l'événement de fermeture
        const handleHidden = () => {
            modalElement.removeEventListener('hidden.bs.modal', handleHidden);
            
            // Nettoyer les backdrops
            setTimeout(() => {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                resolve();
            }, 100);
        };
        
        modalElement.addEventListener('hidden.bs.modal', handleHidden);
        modal.hide();
        
        // Fallback au cas où l'événement ne se déclenche pas
        setTimeout(() => {
            modalElement.removeEventListener('hidden.bs.modal', handleHidden);
            resolve();
        }, 500);
    });
};

    const storeSlider = () => {
        const form = document.getElementById('createSliderForm');
        const submitBtn = document.getElementById('submitSliderBtn');
        
        if (!form.checkValidity()) { form.reportValidity(); return; }
        
        const type = document.getElementById('sliderType').value;
        const videoSourceUrl = document.getElementById('videoSourceUrl');
        const videoSourceUpload = document.getElementById('videoSourceUpload');
        const videoPlatform = document.getElementById('videoPlatform');
        let videoSource = '';
        let videoUrl = '';
        
        if (type === 'video') {
            if (videoSourceUrl && videoSourceUrl.checked) {
                videoSource = 'url';
                videoUrl = document.getElementById('videoUrl').value;
                if (!videoUrl) { showAlert('danger', 'Veuillez entrer l\'URL de la vidéo'); return; }
                if (videoPlatform && !videoPlatform.value) { showAlert('danger', 'Veuillez sélectionner la plateforme vidéo'); return; }
            } else if (videoSourceUpload && videoSourceUpload.checked) {
                videoSource = 'upload';
                const videoFile = document.getElementById('videoFile').files[0];
                if (!videoFile) { showAlert('danger', 'Veuillez sélectionner un fichier vidéo'); return; }
            } else {
                showAlert('danger', 'Veuillez choisir une source de vidéo');
                return;
            }
        }
        
        submitBtn.classList.add('btn-processing');
        submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Création en cours...';
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        if (type === 'video') {
            formData.append('video_source', videoSource);
            if (videoSource === 'url') {
                formData.append('video_url', videoUrl);
                if (videoPlatform) formData.append('video_platform', videoPlatform.value);
            }
        }
        
        $.ajax({
            url: '/sliders',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: async function(response) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
                submitBtn.disabled = false;
                
                if (response.success) {

                     await closeModalSafely('createSliderModal');
                    bootstrap.Modal.getInstance(document.getElementById('createSliderModal')).hide();
                    form.reset();
                    document.getElementById('imagePreview').style.display = 'none';
                    document.getElementById('videoThumbnailPreview').style.display = 'none';
                    resetSelect(document.getElementById('sliderProvince'));
                    resetSelect(document.getElementById('sliderRegion'));
                    resetSelect(document.getElementById('sliderVille'));
                    if (videoSourceUrl) videoSourceUrl.checked = true;
                    document.getElementById('videoUrlSection').style.display = 'block';
                    document.getElementById('videoFileSection').style.display = 'none';
                    document.getElementById('videoUrl').value = '';
                    document.getElementById('videoFile').value = '';
                    loadStatistics();
                    loadSliders(1, currentFilters);
                    showAlert('success', 'Slider créé avec succès !');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de la création');
                }
            },
            error: function(xhr) {
                submitBtn.classList.remove('btn-processing');
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
                submitBtn.disabled = false;
                if (xhr.status === 422) {
                    let msg = 'Veuillez corriger les erreurs:<br>';
                    for (let field in xhr.responseJSON.errors) msg += `- ${xhr.responseJSON.errors[field].join('<br>')}<br>`;
                    showAlert('danger', msg);
                } else {
                    showAlert('danger', 'Erreur lors de la création');
                }
            }
        });
    };

    const updateSlider = () => {
    const form = document.getElementById('editSliderForm');
    const submitBtn = document.getElementById('updateSliderBtn');
    const sliderId = document.getElementById('editSliderId').value;
    
    if (!form.checkValidity()) { 
        form.reportValidity(); 
        return; 
    }
    
    const type = document.getElementById('editSliderType').value;
    const editVideoSourceUrl = document.getElementById('editVideoSourceUrl');
    const editVideoSourceUpload = document.getElementById('editVideoSourceUpload');
    const editVideoPlatform = document.getElementById('editVideoPlatform');
    const editVideoFileInput = document.getElementById('editVideoFile');
    const editVideoUrlInput = document.getElementById('editVideoUrl');
    
    submitBtn.classList.add('btn-processing');
    submitBtn.innerHTML = '<div class="spinner-border spinner-border-sm text-light me-2"></div>Enregistrement...';
    submitBtn.disabled = true;
    
    const formData = new FormData(form);
    formData.append('_method', 'PUT');
    
    // 🔥 CRUCIAL: Gérer correctement les champs vidéo pour l'édition
    if (type === 'video') {
        // Déterminer la source sélectionnée
        if (editVideoSourceUrl && editVideoSourceUrl.checked) {
            // Mode URL
            const videoUrl = editVideoUrlInput ? editVideoUrlInput.value : '';
            
            if (videoUrl && videoUrl.trim() !== '') {
                // Nouvelle URL fournie
                formData.append('edit_video_source', 'url');
                if (editVideoPlatform) {
                    formData.append('edit_video_platform', editVideoPlatform.value);
                }
                // Supprimer le fichier vidéo s'il existe
                formData.delete('edit_video_file');
            } else {
                // Pas de nouvelle URL - ne rien envoyer pour garder l'existant
                formData.delete('edit_video_source');
                formData.delete('edit_video_platform');
                formData.delete('video_url');
                formData.delete('edit_video_file');
            }
        } 
        else if (editVideoSourceUpload && editVideoSourceUpload.checked) {
            // Mode Upload
            if (editVideoFileInput && editVideoFileInput.files.length > 0) {
                // Nouveau fichier sélectionné
                formData.append('edit_video_source', 'upload');
                // Le fichier est déjà dans formData via le champ edit_video_file
                console.log('Nouveau fichier vidéo:', editVideoFileInput.files[0].name);
            } else {
                // Pas de nouveau fichier - ne rien envoyer pour garder l'existant
                formData.delete('edit_video_source');
                formData.delete('edit_video_platform');
                formData.delete('video_url');
                formData.delete('edit_video_file');
            }
        }
    } else {
        // Pour les images, supprimer tous les champs vidéo
        formData.delete('edit_video_source');
        formData.delete('edit_video_platform');
        formData.delete('video_url');
        formData.delete('edit_video_file');
        formData.delete('video_file');
    }
    
    // Debug: Afficher ce qui est envoyé
    console.log('=== UPDATE SLIDER DEBUG ===');
    for (let pair of formData.entries()) {
        if (pair[0] !== 'image' && pair[0] !== 'edit_video_file' && pair[0] !== 'video_file') {
            console.log(pair[0] + ':', pair[1]);
        } else if (pair[0] === 'edit_video_file' && pair[1] instanceof File) {
            console.log(pair[0] + ':', '[FILE]', pair[1].name, '(' + pair[1].size + ' bytes)');
        }
    }
    
    $.ajax({
        url: `/sliders/${sliderId}`,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
            submitBtn.disabled = false;
            
            if (response.success) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSliderModal'));
                modal.hide();
                loadSliders(currentPage, currentFilters);
                loadStatistics();
                showAlert('success', response.message || 'Slider mis à jour avec succès !');
            } else {
                showAlert('danger', response.message || 'Erreur lors de la mise à jour');
            }
        },
        error: function(xhr) {
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
            submitBtn.disabled = false;
            
            console.error('Update error:', xhr);
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Erreur de validation:<br>';
                for (let field in errors) {
                    errorMessage += `- ${field}: ${errors[field].join('<br>')}<br>`;
                }
                showAlert('danger', errorMessage);
            } else if (xhr.status === 500) {
                showAlert('danger', 'Erreur serveur. Vérifiez les logs.');
            } else {
                showAlert('danger', 'Erreur lors de la mise à jour: ' + xhr.status);
            }
        }
    });
};
    const setupImagePreview = () => {
        const createImageInput = document.getElementById('sliderImage');
        if (createImageInput) {
            createImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewImage').src = e.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        const videoThumbnailInput = document.getElementById('videoThumbnail');
        if (videoThumbnailInput) {
            videoThumbnailInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewVideoThumbnail').src = e.target.result;
                        document.getElementById('videoThumbnailPreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
        
        const editImageInput = document.getElementById('editSliderImage');
        if (editImageInput) {
            editImageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('previewEditImage').src = e.target.result;
                        document.getElementById('editImagePreview').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    };

    const setupVideoTypeToggle = () => {
        const sliderType = document.getElementById('sliderType');
        if (sliderType) {
            sliderType.addEventListener('change', function() {
                if (this.value === 'image') {
                    document.getElementById('imageUploadSection').style.display = 'block';
                    document.getElementById('videoUploadSection').style.display = 'none';
                    document.getElementById('sliderImage').required = true;
                } else {
                    document.getElementById('imageUploadSection').style.display = 'none';
                    document.getElementById('videoUploadSection').style.display = 'block';
                    document.getElementById('sliderImage').required = false;
                }
            });
        }
        
        const editSliderType = document.getElementById('editSliderType');
        if (editSliderType) {
            editSliderType.addEventListener('change', function() {
                if (this.value === 'image') {
                    document.getElementById('editImageUploadSection').style.display = 'block';
                    document.getElementById('editVideoUploadSection').style.display = 'none';
                    document.getElementById('currentImageSection').style.display = 'block';
                    document.getElementById('currentVideoSection').style.display = 'none';
                } else {
                    document.getElementById('editImageUploadSection').style.display = 'none';
                    document.getElementById('editVideoUploadSection').style.display = 'block';
                    document.getElementById('currentImageSection').style.display = 'none';
                    document.getElementById('currentVideoSection').style.display = 'block';
                }
            });
        }
    };

    const toggleEditSections = (type) => {
    // Récupérer les éléments avec vérification d'existence
    const editImageUploadSection = document.getElementById('editImageUploadSection');
    const editVideoUploadSection = document.getElementById('editVideoUploadSection');
    const currentImageSection = document.getElementById('currentImageSection');
    const currentVideoSection = document.getElementById('currentVideoSection');
    
    if (type === 'image') {
        if (editImageUploadSection) editImageUploadSection.style.display = 'block';
        if (editVideoUploadSection) editVideoUploadSection.style.display = 'none';
        if (currentImageSection) currentImageSection.style.display = 'block';
        if (currentVideoSection) currentVideoSection.style.display = 'none';
    } else {
        if (editImageUploadSection) editImageUploadSection.style.display = 'none';
        if (editVideoUploadSection) editVideoUploadSection.style.display = 'block';
        if (currentImageSection) currentImageSection.style.display = 'none';
        if (currentVideoSection) currentVideoSection.style.display = 'block';
    }
};

    const setupVideoSourceToggle = () => {
        const videoSourceUrl = document.getElementById('videoSourceUrl');
        const videoSourceUpload = document.getElementById('videoSourceUpload');
        const videoUrlSection = document.getElementById('videoUrlSection');
        const videoFileSection = document.getElementById('videoFileSection');
        const videoUrlInput = document.getElementById('videoUrl');
        const videoFileInput = document.getElementById('videoFile');
        
        if (videoSourceUrl && videoSourceUpload) {
            const toggle = () => {
                if (videoSourceUrl.checked) {
                    videoUrlSection.style.display = 'block';
                    videoFileSection.style.display = 'none';
                    if (videoUrlInput) videoUrlInput.required = true;
                    if (videoFileInput) videoFileInput.required = false;
                    if (videoFileInput) videoFileInput.value = '';
                } else {
                    videoUrlSection.style.display = 'none';
                    videoFileSection.style.display = 'block';
                    if (videoUrlInput) videoUrlInput.required = false;
                    if (videoFileInput) videoFileInput.required = true;
                    if (videoUrlInput) videoUrlInput.value = '';
                }
            };
            videoSourceUrl.addEventListener('change', toggle);
            videoSourceUpload.addEventListener('change', toggle);
        }
        
        const editVideoSourceUrl = document.getElementById('editVideoSourceUrl');
        const editVideoSourceUpload = document.getElementById('editVideoSourceUpload');
        const editVideoUrlSection = document.getElementById('editVideoUrlSection');
        const editVideoFileSection = document.getElementById('editVideoFileSection');
        
        if (editVideoSourceUrl && editVideoSourceUpload) {
            const toggleEdit = () => {
                if (editVideoSourceUrl.checked) {
                    editVideoUrlSection.style.display = 'block';
                    editVideoFileSection.style.display = 'none';
                } else {
                    editVideoUrlSection.style.display = 'none';
                    editVideoFileSection.style.display = 'block';
                }
            };
            editVideoSourceUrl.addEventListener('change', toggleEdit);
            editVideoSourceUpload.addEventListener('change', toggleEdit);
        }
    };

    const setupVideoPlatformToggle = () => {
        const videoPlatform = document.getElementById('videoPlatform');
        const videoUrlHelp = document.getElementById('videoUrlHelp');
        const videoUrlInput = document.getElementById('videoUrl');
        
        if (videoPlatform) {
            videoPlatform.addEventListener('change', function() {
                const platform = this.value;
                let placeholder = '', helpText = '';
                
                switch(platform) {
                    case 'youtube':
                        placeholder = 'https://www.youtube.com/watch?v=xxxxxxxxxxx';
                        helpText = 'Collez l\'URL complète YouTube (ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ)';
                        break;
                    case 'vimeo':
                        placeholder = 'https://vimeo.com/xxxxxxxxx';
                        helpText = 'Collez l\'URL complète Vimeo (ex: https://vimeo.com/123456789)';
                        break;
                    case 'other':
                        placeholder = 'https://...';
                        helpText = 'Collez l\'URL complète de votre vidéo';
                        break;
                }
                if (videoUrlInput) videoUrlInput.placeholder = placeholder;
                if (videoUrlHelp) videoUrlHelp.innerHTML = helpText;
            });
        }
        
        const editVideoPlatform = document.getElementById('editVideoPlatform');
        const editVideoUrlHelp = document.getElementById('editVideoUrlHelp');
        const editVideoUrlInput = document.getElementById('editVideoUrl');
        
        if (editVideoPlatform) {
            editVideoPlatform.addEventListener('change', function() {
                const platform = this.value;
                let placeholder = '', helpText = '';
                
                switch(platform) {
                    case 'youtube':
                        placeholder = 'https://www.youtube.com/watch?v=xxxxxxxxxxx';
                        helpText = 'Collez l\'URL complète YouTube (ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ)';
                        break;
                    case 'vimeo':
                        placeholder = 'https://vimeo.com/xxxxxxxxx';
                        helpText = 'Collez l\'URL complète Vimeo (ex: https://vimeo.com/123456789)';
                        break;
                    case 'other':
                        placeholder = 'https://...';
                        helpText = 'Collez l\'URL complète de votre vidéo';
                        break;
                }
                if (editVideoUrlInput) editVideoUrlInput.placeholder = placeholder;
                if (editVideoUrlHelp) editVideoUrlHelp.innerHTML = helpText;
            });
        }
    };

    const setupVideoUrlPreview = () => {
        const videoUrlInput = document.getElementById('videoUrl');
        const videoUrlPreview = document.getElementById('videoUrlPreview');
        const videoUrlPreviewText = document.getElementById('videoUrlPreviewText');
        
        if (videoUrlInput) {
            videoUrlInput.addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    let platform = 'URL';
                    if (url.includes('youtube.com') || url.includes('youtu.be')) platform = 'YouTube';
                    else if (url.includes('vimeo.com')) platform = 'Vimeo';
                    videoUrlPreviewText.innerHTML = `<i class="fab fa-${platform.toLowerCase()} me-1"></i> ${platform}: ${url}`;
                    videoUrlPreview.style.display = 'block';
                } else {
                    videoUrlPreview.style.display = 'none';
                }
            });
        }
        
        const editVideoUrlInput = document.getElementById('editVideoUrl');
        const editVideoUrlPreview = document.getElementById('editVideoUrlPreview');
        const editVideoUrlPreviewText = document.getElementById('editVideoUrlPreviewText');
        
        if (editVideoUrlInput) {
            editVideoUrlInput.addEventListener('input', function() {
                const url = this.value;
                if (url) {
                    let platform = 'URL';
                    if (url.includes('youtube.com') || url.includes('youtu.be')) platform = 'YouTube';
                    else if (url.includes('vimeo.com')) platform = 'Vimeo';
                    editVideoUrlPreviewText.innerHTML = `<i class="fab fa-${platform.toLowerCase()} me-1"></i> ${platform}: ${url}`;
                    editVideoUrlPreview.style.display = 'block';
                } else {
                    editVideoUrlPreview.style.display = 'none';
                }
            });
        }
    };

    const setupVideoFilePreview = () => {
        const videoFileInput = document.getElementById('videoFile');
        const videoFilePreview = document.getElementById('videoFilePreview');
        const videoFileName = document.getElementById('videoFileName');
        const videoFileSize = document.getElementById('videoFileSize');
        
        if (videoFileInput) {
            videoFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    videoFileName.textContent = file.name;
                    videoFileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    videoFilePreview.style.display = 'block';
                } else {
                    videoFilePreview.style.display = 'none';
                }
            });
        }
        
        const editVideoFileInput = document.getElementById('editVideoFile');
        const editVideoFilePreview = document.getElementById('editVideoFilePreview');
        const editVideoFileName = document.getElementById('editVideoFileName');
        const editVideoFileSize = document.getElementById('editVideoFileSize');
        
        if (editVideoFileInput) {
            editVideoFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    editVideoFileName.textContent = file.name;
                    editVideoFileSize.textContent = `(${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    editVideoFilePreview.style.display = 'block';
                } else {
                    editVideoFilePreview.style.display = 'none';
                }
            });
        }
    };

    const openEditModal = (sliderId) => {
    const slider = allSliders.find(s => s.id === sliderId);
    if (!slider) { showAlert('danger', 'Slider non trouvé'); return; }
    
    document.getElementById('editSliderId').value = slider.id;
    document.getElementById('editSliderName').value = slider.name;
    document.getElementById('editSliderDescription').value = slider.description || '';
    document.getElementById('editSliderType').value = slider.type;
    document.getElementById('editSliderOrder').value = slider.order;
    document.getElementById('editButtonText').value = slider.button_text || '';
    document.getElementById('editButtonUrl').value = slider.button_url || '';
    document.getElementById('editSliderIsActive').checked = slider.is_active;
    
    // Charger la hiérarchie de localisation
    if (slider.country_id) {
        document.getElementById('editSliderCountry').value = slider.country_id;
        document.getElementById('editSliderCountry').dispatchEvent(new Event('change'));
        
        setTimeout(() => {
            if (slider.province_id) {
                document.getElementById('editSliderProvince').value = slider.province_id;
                document.getElementById('editSliderProvince').dispatchEvent(new Event('change'));
                
                setTimeout(() => {
                    if (slider.region_id) {
                        document.getElementById('editSliderRegion').value = slider.region_id;
                        document.getElementById('editSliderRegion').dispatchEvent(new Event('change'));
                        
                        setTimeout(() => {
                            if (slider.ville_id) {
                                document.getElementById('editSliderVille').value = slider.ville_id;
                            }
                        }, 100);
                    }
                }, 100);
            }
        }, 100);
    }
    
    if (slider.type === 'video') {
        // Déterminer la source de la vidéo
        const isUploaded = slider.video_type === 'upload' || (slider.video_path && !slider.video_url);
        const editVideoTypeInput = document.getElementById('editVideoType');
        
        if (isUploaded && slider.video_path) {
            // Mode Upload
            document.getElementById('editVideoSourceUpload').checked = true;
            document.getElementById('editVideoUrlSection').style.display = 'none';
            document.getElementById('editVideoFileSection').style.display = 'block';
            if (editVideoTypeInput) editVideoTypeInput.value = 'upload';
            
            // Afficher la vidéo actuelle
            const currentVideoPreview = document.getElementById('currentVideoPreview');
            currentVideoPreview.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-file-video me-2"></i>
                    <strong>Vidéo uploadée:</strong> ${slider.video_path.split('/').pop()}
                </div>
            `;
        } else {
            // Mode URL
            document.getElementById('editVideoSourceUrl').checked = true;
            document.getElementById('editVideoUrlSection').style.display = 'block';
            document.getElementById('editVideoFileSection').style.display = 'none';
            
            // Définir le type de vidéo
            if (editVideoTypeInput) editVideoTypeInput.value = slider.video_type || 'youtube';
            
            // Définir la plateforme dans le select
            const platformSelect = document.getElementById('editVideoPlatform');
            if (platformSelect) {
                let platform = 'other';
                if (slider.video_type === 'youtube') platform = 'youtube';
                if (slider.video_type === 'vimeo') platform = 'vimeo';
                platformSelect.value = platform;
                platformSelect.dispatchEvent(new Event('change'));
            }
            
            // Définir l'URL
            const videoUrlInput = document.getElementById('editVideoUrl');
            if (videoUrlInput) videoUrlInput.value = slider.video_url || '';
            
            // Déclencher l'événement input pour l'aperçu
            if (videoUrlInput) {
                const inputEvent = new Event('input', { bubbles: true });
                videoUrlInput.dispatchEvent(inputEvent);
            }
            
            // Afficher la vidéo actuelle
            const currentVideoPreview = document.getElementById('currentVideoPreview');
            if (slider.video_url) {
                let platformIcon = 'fa-link';
                let platformName = 'URL';
                if (slider.video_type === 'youtube') { platformIcon = 'fa-youtube'; platformName = 'YouTube'; }
                else if (slider.video_type === 'vimeo') { platformIcon = 'fa-vimeo'; platformName = 'Vimeo'; }
                
                currentVideoPreview.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fab ${platformIcon} me-2"></i>
                        <strong>${platformName}:</strong> ${slider.video_url}
                    </div>
                `;
            } else {
                currentVideoPreview.innerHTML = '<div class="alert alert-warning">Aucune vidéo configurée</div>';
            }
        }
        
        // Afficher le thumbnail actuel
        const currentThumbnailPreview = document.getElementById('currentThumbnailPreview');
        if (currentThumbnailPreview) {
            if (slider.image_path) {
                const imageUrl = slider.image_path.startsWith('http') ? slider.image_path : `/storage/${slider.image_path}`;
                currentThumbnailPreview.innerHTML = `<img src="${imageUrl}" class="img-thumbnail" style="max-width: 200px;">`;
            } else {
                currentThumbnailPreview.innerHTML = '<div class="text-muted">Aucun thumbnail défini</div>';
            }
        }
    } else {
        // Afficher l'image actuelle
        const currentImagePreview = document.getElementById('currentImagePreview');
        if (currentImagePreview) {
            if (slider.image_path) {
                const imageUrl = slider.image_path.startsWith('http') ? slider.image_path : `/storage/${slider.image_path}`;
                currentImagePreview.innerHTML = `<img src="${imageUrl}" class="img-thumbnail" style="max-width: 300px;">`;
            } else {
                currentImagePreview.innerHTML = '<div class="text-muted">Aucune image</div>';
            }
        }
    }
    
    toggleEditSections(slider.type);
    new bootstrap.Modal(document.getElementById('editSliderModal')).show();
};

    const renderPagination = (response) => {
        const pagination = document.getElementById('pagination');
        const paginationInfo = document.getElementById('paginationInfo');
        const start = (response.current_page - 1) * response.per_page + 1;
        const end = Math.min(response.current_page * response.per_page, response.total);
        paginationInfo.textContent = `Affichage de ${start} à ${end} sur ${response.total} slider${response.total > 1 ? 's' : ''}`;
        
        let html = '';
        if (response.prev_page_url) html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page - 1})"><i class="fas fa-chevron-left"></i></a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-left"></i></span></li>`;
        
        let startPage = Math.max(1, response.current_page - 2);
        let endPage = Math.min(response.last_page, startPage + 4);
        if (endPage - startPage + 1 < 5) startPage = Math.max(1, endPage - 4);
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === response.current_page) html += `<li class="page-item active"><span class="page-link-modern">${i}</span></li>`;
            else html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${i})">${i}</a></li>`;
        }
        
        if (response.next_page_url) html += `<li class="page-item"><a class="page-link-modern" href="#" onclick="changePage(${response.current_page + 1})"><i class="fas fa-chevron-right"></i></a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link-modern"><i class="fas fa-chevron-right"></i></span></li>`;
        
        pagination.innerHTML = html;
    };

    const changePage = (page) => { currentPage = page; loadSliders(page, currentFilters); };
    const formatTimeAgo = (date) => {
        const diffDays = Math.floor((new Date() - date) / (1000 * 60 * 60 * 24));
        if (diffDays === 0) return "Aujourd'hui";
        if (diffDays === 1) return 'Hier';
        if (diffDays < 7) return `Il y a ${diffDays} jours`;
        if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaines`;
        if (diffDays < 365) return `Il y a ${Math.floor(diffDays / 30)} mois`;
        return `Il y a ${Math.floor(diffDays / 365)} ans`;
    };
    
    const showLoading = () => {
        document.getElementById('loadingSpinner').style.display = 'flex';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('paginationContainer').style.display = 'none';
    };
    
    const hideLoading = () => document.getElementById('loadingSpinner').style.display = 'none';
    
    const showAlert = (type, message) => {
        const existingAlert = document.querySelector('.alert-custom-modern');
        if (existingAlert) existingAlert.remove();
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-custom-modern alert-dismissible fade show`;
        alert.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    };
    
    const showError = (message) => showAlert('danger', message);
    
    const setupEventListeners = () => {
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadSliders(1, currentFilters), 500);
            });
        }
        
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterSection = document.getElementById('filterSection');
        if (toggleFilterBtn && filterSection) {
            toggleFilterBtn.addEventListener('click', () => {
                const isVisible = filterSection.style.display === 'block';
                filterSection.style.display = isVisible ? 'none' : 'block';
                toggleFilterBtn.innerHTML = isVisible ? '<i class="fas fa-sliders-h me-2"></i>Filtres' : '<i class="fas fa-times me-2"></i>Masquer les filtres';
            });
        }
        
        const applyFiltersBtn = document.getElementById('applyFiltersBtn');
        if (applyFiltersBtn) {
            applyFiltersBtn.addEventListener('click', () => {
                currentFilters = {
                    status: document.getElementById('filterStatus').value,
                    type: document.getElementById('filterType').value,
                    country_id: document.getElementById('filterCountry')?.value || '',
                    province_id: document.getElementById('filterProvince')?.value || '',
                    region_id: document.getElementById('filterRegion')?.value || '',
                    ville_id: document.getElementById('filterVille')?.value || '',
                    date_from: document.getElementById('filterDateFrom').value,
                    date_to: document.getElementById('filterDateTo').value
                };
                loadSliders(1, currentFilters);
            });
        }
        
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => {
                document.getElementById('filterStatus').value = '';
                document.getElementById('filterType').value = '';
                if (document.getElementById('filterCountry')) document.getElementById('filterCountry').value = '';
                const fp = document.getElementById('filterProvince');
                const fr = document.getElementById('filterRegion');
                const fv = document.getElementById('filterVille');
                if (fp) { fp.innerHTML = '<option value="">Toutes les provinces</option>'; fp.disabled = true; }
                if (fr) { fr.innerHTML = '<option value="">Toutes les régions</option>'; fr.disabled = true; }
                if (fv) { fv.innerHTML = '<option value="">Toutes les villes</option>'; fv.disabled = true; }
                document.getElementById('filterDateFrom').value = '';
                document.getElementById('filterDateTo').value = '';
                currentFilters = {};
                loadSliders(1);
            });
        }
        
        document.getElementById('submitSliderBtn')?.addEventListener('click', storeSlider);
        document.getElementById('updateSliderBtn')?.addEventListener('click', updateSlider);
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', deleteSlider);
        document.getElementById('toggleOrderView')?.addEventListener('click', toggleOrderView);
        document.getElementById('saveOrderBtn')?.addEventListener('click', saveOrder);
        document.getElementById('saveOrderBtn2')?.addEventListener('click', saveOrder);
        document.getElementById('cancelOrderBtn')?.addEventListener('click', cancelOrder);
        
        document.getElementById('deleteConfirmationModal')?.addEventListener('hidden.bs.modal', () => {
            sliderToDelete = null;
            document.getElementById('confirmDeleteBtn').innerHTML = '<span class="btn-text"><i class="fas fa-trash me-2"></i>Supprimer définitivement</span>';
            document.getElementById('confirmDeleteBtn').disabled = false;
        });
        
        document.getElementById('createSliderModal')?.addEventListener('hidden.bs.modal', () => {
            document.getElementById('createSliderForm').reset();
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('videoThumbnailPreview').style.display = 'none';
            document.getElementById('imageUploadSection').style.display = 'block';
            document.getElementById('videoUploadSection').style.display = 'none';
            resetSelect(document.getElementById('sliderProvince'));
            resetSelect(document.getElementById('sliderRegion'));
            resetSelect(document.getElementById('sliderVille'));
            const submitBtn = document.getElementById('submitSliderBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Créer le slider';
            submitBtn.disabled = false;
        });
        
        document.getElementById('editSliderModal')?.addEventListener('hidden.bs.modal', () => {
            const submitBtn = document.getElementById('updateSliderBtn');
            submitBtn.classList.remove('btn-processing');
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Enregistrer les modifications';
            submitBtn.disabled = false;
        });
    };


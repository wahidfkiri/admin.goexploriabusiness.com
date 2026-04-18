<header class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            <button class="sidebar-toggle-mobile" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a href="https://goexploriabusiness.com" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="website-link">
                <i class="fas fa-external-link-alt"></i>
                <span>Voir site web</span>
            </a>
        </div>
        
        <div class="header-right">
            <!-- Dynamic Search Container -->
             @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
            <div class="search-container-modern" id="globalSearchContainer">
                <i class="fas fa-search search-icon-modern"></i>
                <input type="text" 
                       class="search-input-modern" 
                       id="globalSearchInput" 
                       placeholder="Rechercher un continent, pays, région, activité, projet..." 
                       autocomplete="off">
                <div class="search-results-modern" id="searchResults" style="display: none;">
                    <div class="search-results-header">
                        <span class="results-title">Résultats récents</span>
                        <button class="clear-results-btn" id="clearSearchResults">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="search-results-list" id="searchResultsList">
                        <!-- Results will be injected here -->
                    </div>
                    <div class="search-results-footer" id="searchResultsFooter" style="display: none;">
                        <a href="#" id="viewAllResultsLink" class="view-all-link">
                            Voir tous les résultats <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
            {{-- Notification Button --}}
            <button class="notification-btn">
                <i class="far fa-bell"></i>
                <!-- <span class="notification-badge">0</span> -->
            </button>

            {{-- Chat Message Button --}}
            <div class="hdr-chat-wrap" id="hdrChatWrap">
                <button class="hdr-chat-btn" id="hdrChatBtn" title="Messages internes">
                    <i class="far fa-comment-dots"></i>
                    <span class="hdr-chat-badge" id="hdrChatBadge" style="display:none">0</span>
                </button>
                <div class="hdr-chat-dropdown" id="hdrChatDropdown">
                    <div class="hdr-chat-dd-head">
                        <span class="hdr-chat-dd-title">
                            <i class="fas fa-comments"></i> Messages
                        </span>
                        <a href="{{ route('internal.chat.index') }}" class="hdr-chat-dd-all">
                            Tout voir <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <div class="hdr-chat-dd-body" id="hdrChatDdBody">
                        <div class="hdr-chat-loading" id="hdrChatLoading">
                            <div class="hdr-chat-spinner"></div>
                        </div>
                        <ul class="hdr-chat-dd-list" id="hdrChatDdList" style="display:none"></ul>
                        <div class="hdr-chat-dd-empty" id="hdrChatDdEmpty" style="display:none">
                            <i class="far fa-comment-dots"></i>
                            <p>Aucune conversation</p>
                        </div>
                    </div>
                    <div class="hdr-chat-dd-foot">
                        <a href="{{ route('internal.chat.new') }}" class="hdr-chat-dd-new">
                            <i class="fas fa-plus"></i> Nouvelle conversation
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-info">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p>Administrateur</p>
                </div>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</header>

{{-- Search Styles --}}
<style>
/* Modern Search Container */
.search-container-modern {
    position: relative;
    width: 380px;
    margin-right: 1rem;
}

.search-icon-modern {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.9rem;
    pointer-events: none;
    transition: color 0.2s ease;
}

.search-input-modern {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
    background: #fff;
    transition: all 0.2s ease;
    outline: none;
}

.search-input-modern:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.search-input-modern:focus + .search-icon-modern {
    color: var(--primary-color);
}

/* Search Results Dropdown */
.search-results-modern {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 40px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.06);
    z-index: 10000;
    overflow: hidden;
    animation: searchDropdownFadeIn 0.2s ease;
}

@keyframes searchDropdownFadeIn {
    from {
        opacity: 0;
        transform: translateY(-8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-results-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}

.results-title {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.clear-results-btn {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    transition: all 0.2s;
    font-size: 0.75rem;
}

.clear-results-btn:hover {
    background: #e2e8f0;
    color: #475569;
}

.search-results-list {
    max-height: 400px;
    overflow-y: auto;
}

.search-results-list::-webkit-scrollbar {
    width: 4px;
}

.search-results-list::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.search-results-list::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Result Item */
.search-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    color: inherit;
    transition: background 0.15s ease;
    border-bottom: 1px solid #f1f5f9;
}

.search-result-item:hover {
    background: #f8fafc;
}

.result-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.result-icon.globe { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
.result-icon.map { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
.result-icon.flag { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
.result-icon.city { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }
.result-icon.activity { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
.result-icon.project { background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff; }
.result-icon.task { background: linear-gradient(135deg, #ec4899, #db2777); color: #fff; }
.result-icon.building { background: linear-gradient(135deg, #14b8a6, #0d9488); color: #fff; }

.result-content {
    flex: 1;
    min-width: 0;
}

.result-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.result-type {
    font-size: 0.65rem;
    font-weight: 500;
    padding: 2px 8px;
    border-radius: 20px;
    background: #f1f5f9;
    color: #475569;
}

.result-subtitle {
    font-size: 0.75rem;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.result-badge {
    font-size: 0.7rem;
    padding: 2px 8px;
    border-radius: 12px;
    background: #f1f5f9;
    color: #475569;
}

.search-results-footer {
    padding: 10px 16px;
    border-top: 1px solid #f1f5f9;
    background: #f8fafc;
    text-align: center;
}

.view-all-link {
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--primary-color);
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: gap 0.2s;
}

.view-all-link:hover {
    gap: 10px;
}

/* Loading State */
.search-loading {
    padding: 32px;
    text-align: center;
    color: #94a3b8;
}

.search-loading .spinner {
    width: 24px;
    height: 24px;
    border: 2px solid #e2e8f0;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    margin: 0 auto 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.search-empty {
    padding: 48px 24px;
    text-align: center;
    color: #94a3b8;
}

.search-empty i {
    font-size: 32px;
    margin-bottom: 12px;
    opacity: 0.5;
}

.search-empty p {
    font-size: 0.85rem;
    margin: 0;
}

/* Recent Searches */
.recent-search-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    font-size: 0.85rem;
    color: #64748b;
    cursor: pointer;
    transition: background 0.15s;
}

.recent-search-item:hover {
    background: #f8fafc;
    color: #1e293b;
}

.recent-search-item i {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Responsive */
@media (max-width: 992px) {
    .search-container-modern {
        width: 250px;
    }
}

@media (max-width: 768px) {
    .search-container-modern {
        display: none;
    }
}
</style>

{{-- Search Script --}}
<script>
(function() {
    'use strict';

    const searchInput = document.getElementById('globalSearchInput');
    const resultsContainer = document.getElementById('searchResults');
    const resultsList = document.getElementById('searchResultsList');
    const resultsFooter = document.getElementById('searchResultsFooter');
    const clearBtn = document.getElementById('clearSearchResults');
    const viewAllLink = document.getElementById('viewAllResultsLink');

    let searchTimeout = null;
    let currentQuery = '';
    let allResults = [];

    // Debounced search function
    function debounceSearch() {
        if (searchTimeout) clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const query = searchInput.value.trim();
            if (query.length >= 2) {
                performSearch(query);
            } else if (query.length === 0) {
                showRecentSearches();
            } else {
                hideResults();
            }
        }, 300);
    }

    // Perform search
    async function performSearch(query) {
        currentQuery = query;
        showLoading();
        
        try {
            const response = await fetch(`/api/global-search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                allResults = data.results;
                renderResults(allResults);
                saveToRecentSearches(query);
            } else {
                showEmpty();
            }
        } catch (error) {
            console.error('Search error:', error);
            showEmpty();
        }
    }

    // Render search results
    function renderResults(results) {
        if (!results || results.length === 0) {
            showEmpty();
            return;
        }

        const limitedResults = results.slice(0, 8);
        
        resultsList.innerHTML = limitedResults.map(item => `
            <a href="${item.url}" class="search-result-item" data-type="${item.type}">
                <div class="result-icon ${getIconClass(item.type)}">
                    <i class="${getIcon(item.type)}"></i>
                </div>
                <div class="result-content">
                    <div class="result-title">
                        ${escapeHtml(item.title)}
                        <span class="result-type">${getTypeLabel(item.type)}</span>
                    </div>
                    <div class="result-subtitle">
                        ${item.subtitle ? escapeHtml(item.subtitle) : ''}
                    </div>
                </div>
                ${item.badge ? `<div class="result-badge">${escapeHtml(item.badge)}</div>` : ''}
            </a>
        `).join('');

        if (results.length > 8) {
            resultsFooter.style.display = 'block';
            viewAllLink.href = `/search?q=${encodeURIComponent(currentQuery)}`;
        } else {
            resultsFooter.style.display = 'none';
        }

        resultsContainer.style.display = 'block';
    }

    // Show recent searches
    function showRecentSearches() {
        const recent = getRecentSearches();
        
        if (recent.length === 0) {
            hideResults();
            return;
        }

        resultsList.innerHTML = `
            <div class="search-recents">
                ${recent.map(query => `
                    <div class="recent-search-item" data-query="${escapeHtml(query)}">
                        <i class="fas fa-history"></i>
                        <span>${escapeHtml(query)}</span>
                    </div>
                `).join('')}
            </div>
        `;
        
        resultsFooter.style.display = 'none';
        resultsContainer.style.display = 'block';
        
        // Add click handlers for recent searches
        document.querySelectorAll('.recent-search-item').forEach(el => {
            el.addEventListener('click', () => {
                const query = el.dataset.query;
                searchInput.value = query;
                performSearch(query);
            });
        });
    }

    // Show loading state
    function showLoading() {
        resultsList.innerHTML = `
            <div class="search-loading">
                <div class="spinner"></div>
                <p>Recherche en cours...</p>
            </div>
        `;
        resultsFooter.style.display = 'none';
        resultsContainer.style.display = 'block';
    }

    // Show empty state
    function showEmpty() {
        resultsList.innerHTML = `
            <div class="search-empty">
                <i class="fas fa-search"></i>
                <p>Aucun résultat trouvé pour "${escapeHtml(currentQuery)}"</p>
            </div>
        `;
        resultsFooter.style.display = 'none';
        resultsContainer.style.display = 'block';
    }

    // Hide results
    function hideResults() {
        resultsContainer.style.display = 'none';
    }

    // Helper: Get icon class
    function getIconClass(type) {
        const classes = {
            continent: 'globe',
            country: 'flag',
            province: 'map',
            region: 'map',
            secteur: 'map',
            ville: 'city',
            activity: 'activity',
            project: 'project',
            task: 'task',
            etablissement: 'building'
        };
        return classes[type] || 'globe';
    }

    // Helper: Get icon
    function getIcon(type) {
        const icons = {
            continent: 'fas fa-globe-americas',
            country: 'fas fa-flag',
            province: 'fas fa-map-marker-alt',
            region: 'fas fa-map',
            secteur: 'fas fa-building',
            ville: 'fas fa-city',
            activity: 'fas fa-puzzle-piece',
            project: 'fas fa-project-diagram',
            task: 'fas fa-tasks',
            etablissement: 'fas fa-store'
        };
        return icons[type] || 'fas fa-search';
    }

    // Helper: Get type label
    function getTypeLabel(type) {
        const labels = {
            continent: 'Continent',
            country: 'Pays',
            province: 'Province',
            region: 'Région',
            secteur: 'Secteur',
            ville: 'Ville',
            activity: 'Activité',
            project: 'Projet',
            task: 'Tâche',
            etablissement: 'Établissement'
        };
        return labels[type] || type;
    }

    // Helper: Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Recent searches storage
    function getRecentSearches() {
        try {
            const recent = localStorage.getItem('global_search_recent');
            return recent ? JSON.parse(recent).slice(0, 5) : [];
        } catch {
            return [];
        }
    }

    function saveToRecentSearches(query) {
        if (!query || query.length < 2) return;
        
        try {
            let recent = getRecentSearches();
            recent = [query, ...recent.filter(q => q !== query)];
            recent = recent.slice(0, 5);
            localStorage.setItem('global_search_recent', JSON.stringify(recent));
        } catch (e) {}
    }

    function clearRecentSearches() {
        localStorage.removeItem('global_search_recent');
        showRecentSearches();
    }

    // Event listeners
    searchInput.addEventListener('input', debounceSearch);
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.trim().length === 0) {
            showRecentSearches();
        }
    });
    
    clearBtn.addEventListener('click', clearRecentSearches);
    
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            hideResults();
        }
    });
    
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            hideResults();
            searchInput.blur();
        }
    });
})();
</script>

{{-- Chat Styles (keep existing) --}}
<style>
/* ── Chat button ── */
.hdr-chat-wrap {
    position: relative;
}

.hdr-chat-btn {
    position: relative;
    background: none;
    border: none;
    color: #64748b;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    transition: color .2s, background .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
}
.hdr-chat-btn:hover {
    color: var(--primary-color);
    background: var(--primary-light);
}
.hdr-chat-btn.active {
    color: var(--primary-color);
    background: var(--primary-light);
}

.hdr-chat-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: var(--danger-color);
    color: #fff;
    border-radius: 10px;
    min-width: 18px;
    height: 18px;
    font-size: 0.65rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid #fff;
    animation: hdrBadgePop .3s cubic-bezier(.34,1.4,.64,1);
}
@keyframes hdrBadgePop {
    from { transform: scale(0); }
    to   { transform: scale(1); }
}

/* Dropdown panel */
.hdr-chat-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: 360px;
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 4px 16px rgba(0,0,0,.06);
    z-index: 9999;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px) scale(.97);
    transition: opacity .2s ease, transform .2s ease, visibility .2s;
    pointer-events: none;
}
.hdr-chat-dropdown.open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    pointer-events: all;
}

.hdr-chat-dropdown::before {
    content: '';
    position: absolute;
    top: -7px;
    right: 12px;
    width: 14px;
    height: 14px;
    background: #fff;
    border-left: 1px solid #e2e8f0;
    border-top: 1px solid #e2e8f0;
    transform: rotate(45deg);
    z-index: 1;
}

.hdr-chat-dd-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px 12px;
    border-bottom: 1px solid #f1f5f9;
}
.hdr-chat-dd-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hdr-chat-dd-title i { color: var(--primary-color); }
.hdr-chat-dd-all {
    font-size: 12px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: opacity .15s;
}
.hdr-chat-dd-all:hover { opacity: .75; }

.hdr-chat-dd-body {
    max-height: 340px;
    overflow-y: auto;
}
.hdr-chat-dd-body::-webkit-scrollbar { width: 4px; }
.hdr-chat-dd-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

.hdr-chat-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
}
.hdr-chat-spinner {
    width: 24px;
    height: 24px;
    border: 2.5px solid #e2e8f0;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: hdrSpin .7s linear infinite;
}
@keyframes hdrSpin { to { transform: rotate(360deg); } }

.hdr-chat-dd-list {
    list-style: none;
    margin: 0;
    padding: 6px 0;
}
.hdr-chat-dd-item a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px;
    text-decoration: none;
    color: inherit;
    transition: background .12s;
}
.hdr-chat-dd-item a:hover { background: #f8fafc; }

.hdr-dd-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-color), #7b5ea7);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    flex-shrink: 0;
    overflow: hidden;
    text-transform: uppercase;
}
.hdr-dd-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.hdr-dd-meta { flex: 1; min-width: 0; }
.hdr-dd-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
}
.hdr-dd-name {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.hdr-dd-time {
    font-size: 11px;
    color: #94a3b8;
    white-space: nowrap;
    flex-shrink: 0;
}
.hdr-dd-preview {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: 2px;
}
.hdr-dd-preview.unread {
    font-weight: 600;
    color: #334155;
}
.hdr-dd-badge {
    background: var(--primary-color);
    color: #fff;
    font-size: 10px;
    font-weight: 800;
    border-radius: 10px;
    padding: 2px 6px;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
}

.hdr-chat-dd-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 36px 20px;
    gap: 10px;
    color: #94a3b8;
}
.hdr-chat-dd-empty i { font-size: 28px; opacity: .4; }
.hdr-chat-dd-empty p { font-size: 13px; margin: 0; }

.hdr-chat-dd-foot {
    border-top: 1px solid #f1f5f9;
    padding: 12px 18px;
}
.hdr-chat-dd-new {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: background .15s;
}
.hdr-chat-dd-new:hover { background: #dde3fb; color: var(--primary-color); }
</style>

{{-- Chat Script (keep existing) --}}
<script>
(function () {
    'use strict';

    const btn       = document.getElementById('hdrChatBtn');
    const dropdown  = document.getElementById('hdrChatDropdown');
    const badge     = document.getElementById('hdrChatBadge');
    const loading   = document.getElementById('hdrChatLoading');
    const list      = document.getElementById('hdrChatDdList');
    const empty     = document.getElementById('hdrChatDdEmpty');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const roomsApiUrl    = '/internal-chat/rooms';
    const heartbeatUrl   = '/internal-chat/heartbeat';

    let isOpen    = false;
    let loaded    = false;
    let totalUnread = 0;

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        isOpen = !isOpen;
        dropdown.classList.toggle('open', isOpen);
        btn.classList.toggle('active', isOpen);

        if (isOpen && !loaded) {
            fetchRooms();
        }
    });

    document.addEventListener('click', (e) => {
        if (!document.getElementById('hdrChatWrap').contains(e.target)) {
            isOpen = false;
            dropdown.classList.remove('open');
            btn.classList.remove('active');
        }
    });

    async function fetchRooms() {
        loading.style.display = 'flex';
        list.style.display    = 'none';
        empty.style.display   = 'none';

        try {
            const res  = await fetch(roomsApiUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin',
            });
            const data = await res.json();
            const rooms = (data.rooms || []).slice(0, 6);

            totalUnread = data.total_unread || 0;
            updateBadge(totalUnread);

            loading.style.display = 'none';
            loaded = true;

            if (!rooms.length) {
                empty.style.display = 'flex';
                return;
            }

            list.innerHTML = rooms.map(r => renderRoomItem(r)).join('');
            list.style.display = 'block';

        } catch (err) {
            loading.style.display = 'none';
            empty.style.display   = 'flex';
            console.error('Chat header fetch error:', err);
        }
    }

    function renderRoomItem(r) {
        const last    = r.last_message;
        const preview = last
            ? (last.type === 'file' ? '📎 Fichier joint' : esc(last.body || '').substring(0, 42))
            : 'Aucun message';
        const time    = last ? relativeTime(last.created_at) : '';
        const unread  = r.unread_count || 0;
        const initials = esc((r.name || '?').substring(0, 2));
        const avatarHtml = r.avatar
            ? `<img src="${esc(r.avatar)}" alt="${esc(r.name)}">`
            : initials;

        return `
            <li class="hdr-chat-dd-item">
                <a href="/admin/chat/room/${r.id}">
                    <div class="hdr-dd-avatar">${avatarHtml}</div>
                    <div class="hdr-dd-meta">
                        <div class="hdr-dd-row">
                            <span class="hdr-dd-name">${esc(r.name)}</span>
                            ${time ? `<span class="hdr-dd-time">${time}</span>` : ''}
                        </div>
                        <div class="hdr-dd-row">
                            <span class="hdr-dd-preview ${unread > 0 ? 'unread' : ''}">${preview}</span>
                            ${unread > 0 ? `<span class="hdr-dd-badge">${unread > 99 ? '99+' : unread}</span>` : ''}
                        </div>
                    </div>
                </a>
            </li>`;
    }

    function updateBadge(count) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }

    async function heartbeat() {
        try {
            const res  = await fetch(heartbeatUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                credentials: 'same-origin',
            });
            const data = await res.json();
            const count = data.total_unread || 0;
            updateBadge(count);

            if (isOpen && count !== totalUnread) {
                totalUnread = count;
                loaded = false;
                fetchRooms();
            } else {
                totalUnread = count;
            }
        } catch (e) { /* silent */ }
    }

    function esc(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function relativeTime(isoStr) {
        if (!isoStr) return '';
        const diff  = Math.floor((Date.now() - new Date(isoStr)) / 1000);
        if (diff < 60)    return 'maintenant';
        if (diff < 3600)  return Math.floor(diff / 60) + 'm';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h';
        return Math.floor(diff / 86400) + 'j';
    }

    heartbeat();
    setInterval(() => {
        if (!document.hidden) {
            heartbeat();
        }
    }, 30000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            heartbeat();
        }
    });
})();
</script>
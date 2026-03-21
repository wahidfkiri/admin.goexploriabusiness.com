@extends('layouts.app')

@section('content')
    
    <main class="dashboard-content">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="page-header-content">
                <div class="page-header-left">
                    <div class="page-header-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h1 class="page-title-modern">Modifier la campagne</h1>
                        <p class="page-subtitle-modern">{{ $campaign->nom }}</p>
                    </div>
                </div>
                <div class="page-header-right">
                    <a href="{{ route('mail-campaigns.show', $campaign) }}" class="btn-back">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
        
        <form action="{{ route('mail-campaigns.update', $campaign) }}" method="POST" id="campaignForm">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Informations principales -->
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="card-title-modern">Informations de la campagne</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="form-group-modern">
                                <label class="form-label-modern required">Nom de la campagne</label>
                                <input type="text" name="nom" class="form-control-modern @error('nom') is-invalid @enderror" 
                                       value="{{ old('nom', $campaign->nom) }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="form-group-modern">
                                <label class="form-label-modern required">Sujet de l'email</label>
                                <input type="text" name="sujet" class="form-control-modern @error('sujet') is-invalid @enderror" 
                                       value="{{ old('sujet', $campaign->sujet) }}" id="emailSubject" required>
                                @error('sujet') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="form-group-modern">
                                <label class="form-label-modern required">Contenu de l'email</label>
                                <div class="editor-toolbar-modern">
                                    <div class="toolbar-group">
                                        <span class="toolbar-label">Variables :</span>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{nom}}')"><i class="fas fa-user"></i> Nom</button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{prenom}}')"><i class="fas fa-user"></i> Prénom</button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{email}}')"><i class="fas fa-envelope"></i> Email</button>
                                        <button type="button" class="toolbar-btn" onclick="insertTag('@{{etablissement}}')"><i class="fas fa-building"></i> Établissement</button>
                                    </div>
                                    <div class="toolbar-divider"></div>
                                    <div class="toolbar-group">
                                        <span class="toolbar-label">Formatage :</span>
                                        <button type="button" class="toolbar-btn" onclick="formatText('bold')"><i class="fas fa-bold"></i></button>
                                        <button type="button" class="toolbar-btn" onclick="formatText('italic')"><i class="fas fa-italic"></i></button>
                                        <button type="button" class="toolbar-btn" onclick="formatText('underline')"><i class="fas fa-underline"></i></button>
                                    </div>
                                </div>
                                <textarea name="contenu" id="emailContent" rows="14" class="form-control-modern editor-textarea @error('contenu') is-invalid @enderror" 
                                          required>{{ old('contenu', $campaign->contenu) }}</textarea>
                                <div class="editor-footer">
                                    <div class="char-counter">
                                        <i class="fas fa-chart-simple"></i>
                                        <span id="charCount">0</span> caractères • <span id="wordCount">0</span> mots
                                    </div>
                                    <div class="editor-tips">
                                        <i class="fas fa-keyboard"></i> Astuce: Utilisez Ctrl+B/I/U pour formater
                                    </div>
                                </div>
                                @error('contenu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Sélection du thème -->
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h3 class="card-title-modern">Thème visuel</h3>
                        </div>
                        <div class="card-body-modern">
                            <div class="theme-grid">
                                @php
                                    $themes = [
                                        'modern' => ['name' => 'Moderne', 'icon' => 'fas fa-chart-line', 'color1' => '#667eea', 'color2' => '#764ba2'],
                                        'elegant' => ['name' => 'Élégant', 'icon' => 'fas fa-feather-alt', 'color1' => '#8b6b4d', 'color2' => '#c7a17b'],
                                        'dynamic' => ['name' => 'Dynamique', 'icon' => 'fas fa-bolt', 'color1' => '#ff6b6b', 'color2' => '#4ecdc4'],
                                    ];
                                    $currentTheme = $campaign->options['theme'] ?? 'modern';
                                @endphp
                                @foreach($themes as $key => $theme)
                                    <div class="theme-card {{ $currentTheme === $key ? 'active' : '' }}" data-theme="{{ $key }}" onclick="selectTheme('{{ $key }}')">
                                        <div class="theme-preview" style="background: linear-gradient(135deg, {{ $theme['color1'] }}, {{ $theme['color2'] }});">
                                            <i class="{{ $theme['icon'] }}"></i>
                                        </div>
                                        <div class="theme-info">
                                            <div class="theme-name">{{ $theme['name'] }}</div>
                                            <div class="theme-description">Design {{ strtolower($theme['name']) }} et professionnel</div>
                                        </div>
                                        <div class="theme-radio">
                                            <input type="radio" name="theme" value="{{ $key }}" {{ $currentTheme === $key ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Prévisualisation -->
                    <div class="card-modern">
                        <div class="card-header-modern">
                            <div class="card-header-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="card-title-modern">Prévisualisation</h3>
                            <button type="button" class="btn-refresh" onclick="refreshPreview()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body-modern p-0">
                            <div class="preview-container">
                                <div class="preview-toolbar">
                                    <div class="preview-device-selector">
                                        <button type="button" class="device-btn active" onclick="setPreviewDevice('desktop')"><i class="fas fa-desktop"></i></button>
                                        <button type="button" class="device-btn" onclick="setPreviewDevice('tablet')"><i class="fas fa-tablet-alt"></i></button>
                                        <button type="button" class="device-btn" onclick="setPreviewDevice('mobile')"><i class="fas fa-mobile-alt"></i></button>
                                    </div>
                                </div>
                                <div class="preview-frame-container">
                                    <iframe id="previewFrame" class="preview-frame"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions-modern">
                <div class="actions-left">
                    <a href="{{ route('mail-campaigns.show', $campaign) }}" class="btn-clear">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
                <div class="actions-right">
                    <button type="submit" name="action" value="draft" class="btn-draft">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentTheme = '{{ $campaign->options['theme'] ?? 'modern' }}';
        let currentDevice = 'desktop';
        let previewTimeout = null;
        
        $(document).ready(function() {
            updateCharacterCount();
            refreshPreview();
            
            $('#emailSubject, #emailContent').on('input', function() {
                clearTimeout(previewTimeout);
                previewTimeout = setTimeout(refreshPreview, 800);
                updateCharacterCount();
            });
        });
        
        function selectTheme(theme) {
            currentTheme = theme;
            $('.theme-card').removeClass('active');
            $(`.theme-card[data-theme="${theme}"]`).addClass('active');
            $(`input[name="theme"][value="${theme}"]`).prop('checked', true);
            refreshPreview();
        }
        
        function refreshPreview() {
            const subject = $('#emailSubject').val() || 'Sujet de l\'email';
            const content = $('#emailContent').val() || '<p>Contenu de votre email...</p>';
            
            const previewData = {
                subject: subject,
                content: content,
                prenom: 'Cher',
                nom: 'abonné',
                tracking: { open_tracker: '#', unsubscribe_url: '#', click_tracker: '#' }
            };
            
            $.ajax({
                url: '{{ route("mail-campaigns.preview-theme") }}',
                type: 'POST',
                data: {
                    theme: currentTheme,
                    data: previewData,
                    _token: '{{ csrf_token() }}'
                },
                success: function(html) {
                    const frame = $('#previewFrame')[0];
                    const doc = frame.contentDocument || frame.contentWindow.document;
                    doc.open();
                    doc.write(html);
                    doc.close();
                    applyDeviceStyle();
                }
            });
        }
        
        function setPreviewDevice(device) {
            currentDevice = device;
            $('.device-btn').removeClass('active');
            $(`.device-btn:has(.fa-${device === 'desktop' ? 'desktop' : device === 'tablet' ? 'tablet-alt' : 'mobile-alt'})`).addClass('active');
            applyDeviceStyle();
        }
        
        function applyDeviceStyle() {
            const widths = { desktop: '100%', tablet: '768px', mobile: '375px' };
            $('#previewFrame').css('width', widths[currentDevice]);
        }
        
        function insertTag(tag) {
            const textarea = document.getElementById('emailContent');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + tag + text.substring(end);
            textarea.selectionStart = start + tag.length;
            textarea.focus();
            refreshPreview();
        }
        
        function formatText(format) {
            const textarea = document.getElementById('emailContent');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            if (start === end) return;
            
            const selectedText = textarea.value.substring(start, end);
            let formatted = '';
            switch(format) {
                case 'bold': formatted = '<strong>' + selectedText + '</strong>'; break;
                case 'italic': formatted = '<em>' + selectedText + '</em>'; break;
                case 'underline': formatted = '<u>' + selectedText + '</u>'; break;
            }
            const text = textarea.value;
            textarea.value = text.substring(0, start) + formatted + text.substring(end);
            textarea.selectionStart = start + formatted.length;
            textarea.focus();
            refreshPreview();
        }
        
        function updateCharacterCount() {
            const content = $('#emailContent').val() || '';
            $('#charCount').text(content.length);
            $('#wordCount').text(content.trim().split(/\s+/).filter(w => w.length > 0).length);
        }
    </script>
    <style>
        /* Page Header Modern */
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 28px 32px;
            margin-bottom: 32px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        
        .page-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .page-header-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        
        .page-title-modern {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .page-subtitle-modern {
            color: rgba(255,255,255,0.8);
            margin: 5px 0 0;
            font-size: 14px;
        }
        
        .btn-back {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateX(-3px);
        }
        
        /* Cards Modern */
        .card-modern {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 24px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        
        .card-header-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid #eef2f6;
            background: #fafbfc;
        }
        
        .card-header-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .card-title-modern {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            flex: 1;
        }
        
        .card-badge {
            background: #eef2ff;
            color: #667eea;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .card-body-modern {
            padding: 24px;
        }
        
        /* Form Groups */
        .form-group-modern {
            margin-bottom: 24px;
        }
        
        .form-label-modern {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-label-modern.required:after {
            content: "*";
            color: #ef476f;
            margin-left: 4px;
        }
        
        .input-group-modern {
            position: relative;
        }
        
        .form-control-modern {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control-modern:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
            outline: none;
        }
        
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .form-text-modern {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            display: block;
        }
        
        .subject-preview {
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-top: 8px;
            color: #475569;
        }
        
        /* Editor Toolbar */
        .editor-toolbar-modern {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px 12px 0 0;
            padding: 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        
        .toolbar-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }
        
        .toolbar-btn {
            padding: 6px 12px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #334155;
        }
        
        .toolbar-btn:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }
        
        .toolbar-divider {
            width: 1px;
            height: 30px;
            background: #e2e8f0;
        }
        
        .editor-textarea {
            border-radius: 0 0 12px 12px;
            border-top: none;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 13px;
            line-height: 1.6;
            resize: vertical;
        }
        
        .editor-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 12px 12px;
            font-size: 12px;
            color: #64748b;
        }
        
        .editor-tips i, .char-counter i {
            margin-right: 4px;
        }
        
        .separator-dot {
            margin: 0 8px;
        }
        
        /* Option Cards */
        .option-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 16px;
        }
        
        .option-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }
        
        .option-icon {
            font-size: 18px;
            color: #667eea;
        }
        
        .option-title {
            font-weight: 600;
            color: #1e293b;
        }
        
        .form-switch-modern .form-check-input {
            width: 40px;
            height: 20px;
            cursor: pointer;
        }
        
        .form-switch-modern .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .datetime-picker {
            position: relative;
        }
        
        .datetime-picker i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .datetime-picker input {
            padding-left: 36px;
        }
        
        .segmentation-stats {
            margin-top: 12px;
        }
        
        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #eef2ff;
            border-radius: 20px;
            font-size: 12px;
            color: #667eea;
        }
        
        /* Theme Grid */
        .theme-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .theme-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            border: 2px solid #eef2f6;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-card:hover {
            border-color: #667eea;
            background: #f8fafc;
        }
        
        .theme-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102,126,234,0.05), rgba(118,75,162,0.05));
        }
        
        .theme-preview {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .theme-info {
            flex: 1;
        }
        
        .theme-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }
        
        .theme-description {
            font-size: 11px;
            color: #64748b;
        }
        
        .theme-radio input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        /* Preview */
        .preview-container {
            background: #f1f5f9;
            border-radius: 16px;
            overflow: hidden;
        }
        
        .preview-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border-bottom: 1px solid #eef2f6;
        }
        
        .preview-device-selector {
            display: flex;
            gap: 8px;
        }
        
        .device-btn {
            padding: 8px 12px;
            background: #f1f5f9;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #475569;
        }
        
        .device-btn.active {
            background: #667eea;
            color: white;
        }
        
        .preview-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
        }
        
        .preview-frame {
            width: 100%;
            height: 450px;
            border: none;
            background: white;
        }
        
        /* Stats Mini Grid */
        .stats-mini-grid {
            display: flex;
            gap: 16px;
        }
        
        .stat-mini-item {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .stat-mini-icon {
            width: 40px;
            height: 40px;
            background: #eef2ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 18px;
        }
        
        .stat-mini-info {
            display: flex;
            flex-direction: column;
        }
        
        .stat-mini-value {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        
        .stat-mini-label {
            font-size: 11px;
            color: #64748b;
        }
        
        /* Form Actions */
        .form-actions-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 32px;
            padding: 20px 24px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        
        .actions-left, .actions-right {
            display: flex;
            gap: 12px;
        }
        
        .btn-clear {
            padding: 12px 20px;
            background: #f1f5f9;
            border: none;
            border-radius: 12px;
            color: #475569;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-clear:hover {
            background: #e2e8f0;
        }
        
        .btn-draft, .btn-schedule, .btn-send {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-draft {
            background: #f1f5f9;
            color: #475569;
        }
        
        .btn-draft:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }
        
        .btn-schedule {
            background: #ffd166;
            color: #2d3748;
        }
        
        .btn-schedule:hover {
            background: #ffc107;
            transform: translateY(-1px);
        }
        
        .btn-send {
            background: linear-gradient(135deg, #06b48a, #049a72);
            color: white;
        }
        
        .btn-send:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(6,180,138,0.3);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .page-header-modern { padding: 20px; }
            .page-title-modern { font-size: 22px; }
            .form-actions-modern { flex-direction: column; }
            .actions-left, .actions-right { width: 100%; justify-content: center; }
            .stats-mini-grid { flex-wrap: wrap; }
        }
        
        @media (max-width: 768px) {
            .page-header-left { flex-direction: column; text-align: center; width: 100%; }
            .editor-toolbar-modern { flex-direction: column; }
            .toolbar-divider { display: none; }
        }
    </style>
@endsection
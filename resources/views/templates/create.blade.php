{{-- resources/views/templates/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Scrape New Template')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-spider"></i> Scrape New Website Template
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('scrape.templates.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Step 1: Enter Website URL</h5>
                        <div class="mb-3">
                            <label for="url" class="form-label">
                                <i class="fas fa-link"></i> Website URL *
                            </label>
                            <input type="url" class="form-control form-control-lg" 
                                   id="url" name="url" 
                                   value="{{ old('url') }}"
                                   placeholder="https://example.com" 
                                   required autofocus>
                            <div class="form-text">
                                Enter the full URL of the website you want to scrape.
                            </div>
                            @error('url')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-font"></i> Template Name
                                    </label>
                                    <input type="text" class="form-control" 
                                           id="name" name="name" 
                                           value="{{ old('name') }}"
                                           placeholder="e.g., Example Homepage">
                                    <div class="form-text">
                                        Optional. Auto-generated from URL if left blank.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-align-left"></i> Description
                                    </label>
                                    <textarea class="form-control" id="description" 
                                              name="description" rows="2"
                                              placeholder="Optional description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Step 2: What will be extracted?</h5>
                        
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-code"></i> HTML Content
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Full HTML of &lt;body&gt; tag</li>
                                        <li>All text content</li>
                                        <li>Page structure</li>
                                        <li>Images and links</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-paint-brush"></i> CSS Styles
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Inline &lt;style&gt; tags</li>
                                        <li>External CSS files</li>
                                        <li>Inline style attributes</li>
                                        <li>All styling rules</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Templates
                        </a>
                        
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-spider"></i> Start Scraping
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Examples Card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb"></i> Example URLs
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Simple Landing Page</h6>
                                <code class="d-block mb-2">https://getbootstrap.com</code>
                                <small class="text-muted">Good for CSS framework extraction</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>News Article</h6>
                                <code class="d-block mb-2">https://example.com/article</code>
                                <small class="text-muted">Rich text content example</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>E-commerce Product</h6>
                                <code class="d-block mb-2">https://example.com/product/123</code>
                                <small class="text-muted">Complex layout with images</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Blog Template</h6>
                                <code class="d-block mb-2">https://example.com/blog</code>
                                <small class="text-muted">Content-heavy with styling</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle"></i> Important Notes
                        </h6>
                        <ul class="mb-0 small">
                            <li>Scraping may take 10-30 seconds depending on website size</li>
                            <li>Some websites may block scraping attempts</li>
                            <li>Large websites may have incomplete CSS extraction</li>
                            <li>JavaScript-rendered content may not be captured</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Loading Indicator (no modal) -->
<div id="loadingIndicator" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white;">
        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h4 class="mt-3">Scraping Website...</h4>
        <p>Please wait while we extract HTML and CSS content</p>
        <div class="progress" style="width: 300px; margin: 0 auto;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
        </div>
    </div>
</div>

<!-- Batch Scrape Section -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-list"></i> Batch URL Scraping
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('batch.scrape') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Enter multiple URLs (one per line):</label>
                <textarea name="urls" class="form-control" rows="5" 
                          placeholder="https://example.com
https://example2.com
https://example3.com" required></textarea>
                <div class="form-text">Each URL will be scraped separately. This may take several minutes.</div>
            </div>
            <button type="submit" class="btn btn-info">
                <i class="fas fa-spider"></i> Start Batch Scrape
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    #submitBtn {
        min-width: 150px;
    }
    
    .form-control-lg {
        font-size: 1.1rem;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple form submission handler
    document.querySelector('form').addEventListener('submit', function(e) {
        const url = document.getElementById('url').value;
        
        if (!url) {
            e.preventDefault();
            alert('Please enter a URL');
            return false;
        }
        
        // Validate URL format
        try {
            new URL(url);
        } catch (err) {
            e.preventDefault();
            alert('Please enter a valid URL (e.g., https://example.com)');
            return false;
        }
        
        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';
        
        // Disable submit button to prevent double submission
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scraping...';
        
        return true;
    });
    
    // Auto-generate name from URL
    document.getElementById('url').addEventListener('blur', function() {
        const urlInput = this.value;
        const nameInput = document.getElementById('name');
        
        if (urlInput && !nameInput.value) {
            // Extract domain name
            try {
                const url = new URL(urlInput);
                let name = url.hostname.replace('www.', '');
                name = name.split('.')[0];
                name = name.charAt(0).toUpperCase() + name.slice(1);
                
                // Add path if not too long
                if (url.pathname !== '/') {
                    const path = url.pathname.replace(/[^a-zA-Z0-9]/g, ' ');
                    if (path.length < 20) {
                        name += ' ' + path.trim();
                    }
                }
                
                nameInput.value = name;
            } catch (e) {
                // Invalid URL, do nothing
            }
        }
    });
    
    // Show example URLs on click
    document.querySelectorAll('.card.mb-3').forEach(card => {
        card.addEventListener('click', function() {
            const code = this.querySelector('code');
            if (code) {
                document.getElementById('url').value = code.textContent.trim();
                // Trigger blur to auto-generate name
                document.getElementById('url').dispatchEvent(new Event('blur'));
            }
        });
    });
</script>
@endpush
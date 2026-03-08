{{-- resources/views/templates/show.blade.php --}}
@extends('layouts.app')

@section('title', $template->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('templates.index') }}">Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($template->name, 30) }}</li>
            </ol>
        </nav>
        <h1 class="h2 mb-0">
            <i class="fas fa-file-code text-primary"></i> {{ $template->name }}
        </h1>
        <p class="text-muted mb-0">
            <i class="fas fa-link"></i> 
            <a href="{{ $template->url }}" target="_blank" class="text-decoration-none">
                {{ $template->url }}
            </a>
        </p>
    </div>
    
    <div class="btn-group">
        <a href="{{ route('templates.preview', $template) }}" 
           target="_blank" class="btn btn-success">
            <i class="fas fa-eye"></i> Preview
        </a>
        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" 
                data-bs-toggle="dropdown">
            <span class="visually-hidden">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu">
            <li>
                <a class="dropdown-item" href="{{ route('templates.preview', $template) }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Preview in New Tab
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('templates.raw-html', $template) }}" target="_blank">
                    <i class="fas fa-code"></i> View Raw HTML
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('templates.raw-css', $template) }}" target="_blank">
                    <i class="fas fa-paint-brush"></i> View Raw CSS
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Stats & Actions -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">HTML Size</h6>
                <h4 class="card-title">{{ number_format(strlen($template->html_content)) }} chars</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">CSS Size</h6>
                <h4 class="card-title">
                    @if($template->css_content)
                        {{ number_format(strlen($template->css_content)) }} chars
                    @else
                        No CSS
                    @endif
                </h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Scraped</h6>
                <h4 class="card-title">{{ $template->created_at->format('M d, Y') }}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Actions</h6>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('templates.edit', $template) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button onclick="copyToClipboard('{{ $template->html_content }}', 'copy-html-btn')" 
                            id="copy-html-btn" class="btn btn-outline-secondary">
                        <i class="fas fa-copy"></i> Copy HTML
                    </button>
                    <form action="{{ route('templates.destroy', $template) }}" method="POST" 
                          class="d-inline" onsubmit="return confirm('Delete this template?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Metadata & Info -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> Template Information
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th width="40%">Name:</th>
                        <td>{{ $template->name }}</td>
                    </tr>
                    <tr>
                        <th>URL:</th>
                        <td>
                            <a href="{{ $template->url }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($template->url, 40) }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $template->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $template->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>
                
                <h6 class="mt-4 mb-3">
                    <i class="fas fa-tags"></i> Extracted Metadata
                </h6>
                
                @if(!empty($metadata) && count(array_filter($metadata)) > 0)
                    <div class="list-group">
                        @foreach($metadata as $key => $value)
                            @if(!empty($value))
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <strong class="text-capitalize">{{ str_replace('_', ' ', $key) }}:</strong>
                                        <span class="text-muted">{{ Str::limit($value, 50) }}</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No metadata extracted.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Right Column: Content Preview -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="contentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="html-tab" data-bs-toggle="tab" 
                                data-bs-target="#html-content" type="button">
                            <i class="fas fa-code"></i> HTML Preview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="css-tab" data-bs-toggle="tab" 
                                data-bs-target="#css-content" type="button">
                            <i class="fas fa-paint-brush"></i> CSS Content
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="contentTabContent">
                    <!-- HTML Tab -->
                    <div class="tab-pane fade show active" id="html-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">HTML Content Preview</h6>
                            <div class="btn-group btn-group-sm">
                                <button onclick="copyToClipboard('{{ addslashes($template->html_content) }}', 'copy-html-tab-btn')" 
                                        id="copy-html-tab-btn" class="btn btn-outline-secondary">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <a href="{{ route('templates.raw-html', $template) }}" 
                                   target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Full View
                                </a>
                            </div>
                        </div>
                        
                        <div class="code-block" style="max-height: 400px; overflow-y: auto;">
                            <pre><code class="html">{{ htmlspecialchars(Str::limit($template->html_content, 2000)) }}</code></pre>
                        </div>
                    </div>
                    
                    <!-- CSS Tab -->
                    <div class="tab-pane fade" id="css-content">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">CSS Content</h6>
                            <div class="btn-group btn-group-sm">
                                @if($template->css_content)
                                    <button onclick="copyToClipboard('{{ addslashes($template->css_content) }}', 'copy-css-btn')" 
                                            id="copy-css-btn" class="btn btn-outline-secondary">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                @endif
                                <a href="{{ route('templates.raw-css', $template) }}" 
                                   target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt"></i> Full View
                                </a>
                            </div>
                        </div>
                        
                        @if($template->css_content)
                            <div class="code-block" style="max-height: 400px; overflow-y: auto;">
                                <pre><code class="css">{{ htmlspecialchars(Str::limit($template->css_content, 2000)) }}</code></pre>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No CSS content was extracted from this page.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('templates.preview', $template) }}" 
                           target="_blank" class="btn btn-success w-100">
                            <i class="fas fa-desktop"></i> Preview with CSS
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('templates.edit', $template) }}" 
                           class="btn btn-primary w-100">
                            <i class="fas fa-edit"></i> Edit Template Info
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('templates.create') }}?url={{ urlencode($template->url) }}" 
                           class="btn btn-info w-100">
                            <i class="fas fa-redo"></i> Re-scrape URL
                        </a>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('templates.destroy', $template) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this template?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Template
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize highlight.js for tabs
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight code when tab is shown
        document.getElementById('html-tab').addEventListener('shown.bs.tab', function() {
            document.querySelectorAll('#html-content code').forEach(function(block) {
                hljs.highlightElement(block);
            });
        });
        
        document.getElementById('css-tab').addEventListener('shown.bs.tab', function() {
            document.querySelectorAll('#css-content code').forEach(function(block) {
                hljs.highlightElement(block);
            });
        });
    });
</script>
@endpush
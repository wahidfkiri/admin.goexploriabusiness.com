{{-- resources/views/templates/index.blade.php --}}
@extends('layouts.app')

@section('title', 'All Templates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="fas fa-file-code text-primary"></i> 
        Scraped Templates
        <span class="badge bg-secondary">{{ $templates->total() }}</span>
    </h1>
    
    <div>
        <a href="{{ route('templates.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> New Scrape
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('templates.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by name or URL..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@if($templates->count() > 0)
    <div class="row">
        @foreach($templates as $template)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-truncate" title="{{ $template->name }}">
                            <i class="fas fa-file-alt"></i> {{ Str::limit($template->name, 30) }}
                        </h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                    type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('templates.show', $template) }}">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('templates.preview', $template) }}">
                                        <i class="fas fa-desktop"></i> Preview
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('templates.edit', $template) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('templates.destroy', $template) }}" 
                                          method="POST" id="delete-form-{{ $template->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger" 
                                                onclick="if(confirm('Delete this template?')) document.getElementById('delete-form-{{ $template->id }}').submit()">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text small text-muted mb-2">
                            <i class="fas fa-link"></i> 
                            <a href="{{ $template->url }}" target="_blank" class="text-decoration-none">
                                {{ Str::limit($template->url, 40) }}
                            </a>
                        </p>
                        
                        <div class="mb-3">
                            <span class="badge bg-light text-dark border me-1">
                                <i class="fas fa-calendar"></i> 
                                {{ $template->created_at->format('M d, Y') }}
                            </span>
                            
                            @if($template->css_content)
                                <span class="badge bg-info">
                                    <i class="fas fa-css3-alt"></i> CSS
                                </span>
                            @endif
                            
                            <span class="badge bg-secondary">
                                {{ Str::length($template->html_content) }} chars
                            </span>
                        </div>
                        
                        <div class="code-block small mb-3">
                            <pre><code class="html">{{ Str::limit(strip_tags($template->html_content), 150) }}</code></pre>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('templates.show', $template) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                            <a href="{{ route('templates.preview', $template) }}" 
                               target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-external-link-alt"></i> Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $templates->withQueryString()->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
        <h3>No Templates Found</h3>
        <p class="text-muted mb-4">No templates have been scraped yet, or no templates match your search criteria.</p>
        <a href="{{ route('templates.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-spider"></i> Scrape Your First Template
        </a>
    </div>
@endif
@endsection

@push('styles')
<style>
    .card-header h6 {
        flex: 1;
        min-width: 0;
    }
    
    .dropdown-toggle::after {
        display: none;
    }
</style>
@endpush
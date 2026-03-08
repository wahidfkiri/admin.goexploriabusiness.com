<?php
// app/Http/Controllers/TemplateScraperController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplateScraperService;
use App\Models\Template;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TemplateScraperController extends Controller
{
    protected $scraperService;
    
    public function __construct(TemplateScraperService $scraperService)
    {
        $this->scraperService = $scraperService;
    }
    
    // Display all templates (with AJAX support)
    public function index(Request $request)
    {
        $query = Template::query();
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
        }
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->get('category'));
        }
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        $templates = $query->latest()->paginate(8);
        
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $templates->items(),
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
                'prev_page_url' => $templates->previousPageUrl(),
                'next_page_url' => $templates->nextPageUrl(),
            ]);
        }
        
        return view('templates.test', compact('templates'));
    }
    
    // Show create form
    public function create()
    {
        return view('templates.create');
    }
    
    // Store new template
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // try {
            $template = $this->scraperService->scrapeAndSave(
                $request->input('url'),
                $request->input('name')
            );
            
            // if ($template && $request->has('description')) {
            //     $template->update([
            //         'metadata' => array_merge(
            //             $template->metadata ?? [],
            //             ['description' => $request->input('description')]
            //         )
            //     ]);
            // }
            
            return redirect()->route('templates.show', $template)
                ->with('success', 'Template scraped and saved successfully!');
            
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with('error', 'Failed to scrape template: ' . $e->getMessage())
        //         ->withInput();
        // }
    }
    
    // Show single template
    public function show(Template $template)
    {
        $metadata = $template->metadata ?? [];
        $htmlPreview = Str::limit(strip_tags($template->html_content), 500);
        $cssPreview = Str::limit($template->css_content, 500);
        
        return view('templates.show', compact('template', 'metadata', 'htmlPreview', 'cssPreview'));
    }
    
    // Edit template
    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }
    
    // Update template
    public function update(Request $request, Template $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $metadata = $template->metadata ?? [];
        $metadata['description'] = $validated['description'] ?? null;
        
        $template->update([
            'name' => $validated['name'],
            'metadata' => $metadata,
        ]);
        
        return redirect()->route('templates.show', $template)
            ->with('success', 'Template updated successfully!');
    }
    
    // Delete template
    public function destroy(Template $template)
    {
        $template->delete();
        
        return redirect()->route('templates.index')
            ->with('success', 'Template deleted successfully!');
    }
    
    // Scrape immediately from show page
    public function scrapeNow(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);
        
        try {
            $template = $this->scraperService->scrapeAndSave(
                $request->input('url'),
                $request->input('name', 'Re-scraped Template')
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Template scraped successfully!',
                'redirect' => route('templates.show', $template)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Preview template with applied CSS
    public function preview(Template $template)
    {
        return view('templates.preview', compact('template'));
    }
    
    // Show raw HTML
    public function rawHtml(Template $template)
    {
        return response($template->html_content)
            ->header('Content-Type', 'text/html');
    }
    
    // Show raw CSS
    public function rawCss(Template $template)
    {
        return response($template->css_content)
            ->header('Content-Type', 'text/css');
    }
    
    // Batch scraping
    public function batchScrape(Request $request)
    {
        $request->validate([
            'urls' => 'required|string',
        ]);
        
        $urls = array_filter(
            array_map('trim', explode("\n", $request->input('urls')))
        );
        
        $results = $this->scraperService->scrapeMultiple($urls);
        
        $successful = count(array_filter($results, fn($r) => $r['success']));
        
        return redirect()->route('templates.index')
            ->with('success', "Successfully scraped {$successful} out of " . count($urls) . " URLs.");
    }
    
    // API Methods
    public function apiIndex()
    {
        $templates = Template::select(['id', 'name', 'url', 'created_at'])
            ->latest()
            ->paginate(20);
        
        return response()->json($templates);
    }
    
    public function apiScrape(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'name' => 'nullable|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $template = $this->scraperService->scrapeAndSave(
                $request->input('url'),
                $request->input('name')
            );
            
            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template scraped successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function apiShow(Template $template)
    {
        return response()->json([
            'success' => true,
            'data' => $template
        ]);
    }
}
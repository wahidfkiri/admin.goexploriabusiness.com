<?php
// app/Http/Controllers/GeminiController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiGeneratorService;
use App\Models\Template;

class GeminiController extends Controller
{
    protected $geminiService;
    
    public function __construct(GeminiGeneratorService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    
    public function generate(Request $request)
    {
        // $request->validate([
        //     'prompt' => 'required|string|min:5|max:1000',
        //     'name' => 'nullable|string|max:255',
        // ]);
        
       // try {
            $options = [
                'name' => 'test'
            ];
            
           // if ($request->has('style')) {
                $options['style'] = 'Bootstrap';
          //  }
            
            $template = $this->geminiService->generateFromPrompt(
                'Génerer une page login',
                $options
            );
            
            return redirect()->route('templates.show', $template)
                ->with('success', 'Template généré avec Gemini AI!');
                
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->with('error', 'Erreur: ' . $e->getMessage())
        //         ->withInput();
        // }
    }
    
    public function test()
    {
        $result = $this->geminiService->testConnection();
        
        return response()->json($result);
    }
}
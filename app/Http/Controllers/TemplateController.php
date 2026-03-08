<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemplateController extends Controller
{
    /**
     * Liste des templates
     */
    public function index()
    {
        try {
            $templates = Template::orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load templates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sauvegarder un template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'html' => 'required|string',
            'css' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $template = Template::create([
                'name' => $request->name,
                'description' => $request->description,
                'html_content' => $request->html,
                'css_content' => $request->css ?? '',
                'thumbnail' => $this->generateThumbnail($request->html),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template saved successfully',
                'data' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un template
     */
    public function show($id)
    {
        try {
            $template = Template::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found'
            ], 404);
        }
    }

    /**
     * Mettre à jour un template
     */
    public function update(Request $request, $id)
    {
        $template = Template::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'html' => 'required|string',
            'css' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $template->update([
                'name' => $request->name,
                'description' => $request->description,
                'html_content' => $request->html,
                'css_content' => $request->css ?? '',
                'thumbnail' => $this->generateThumbnail($request->html),
                'is_public' => $request->is_public ?? $template->is_public
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Template updated successfully',
                'data' => $template
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un template
     */
    public function destroy($id)
    {
        try {
            $template = Template::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $template->delete();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template'
            ], 500);
        }
    }

    /**
     * Prévisualiser un template
     */
    public function preview($id)
    {
        try {
            $template = Template::where('id', $id)
                ->where(function($query) {
                    $query->where('user_id', auth()->id());
                        //   ->orWhere('is_public', true);
                })
                ->firstOrFail();

            $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>' . $template->css_content . '</style>
</head>
<body>
    ' . $template->html_content . '
</body>
</html>';

            return response($html)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Templates démo
     */
    public function demoTemplates()
    {
        try {
            $templates = Template::where('is_public', true)
                ->where('is_demo', true)
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load demo templates'
            ], 500);
        }
    }

    /**
     * Rechercher des templates
     */
    public function search($query)
    {
        try {
            $templates = Template::where(function($q) use ($query) {
                    $q->where('user_id', auth()->id())
                      ->orWhere('is_public', true);
                })
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }

    /**
     * Cloner un template
     */
    public function clone($id)
    {
        try {
            $template = Template::where('id', $id)
                ->where(function($query) {
                    $query->where('user_id', auth()->id())
                          ->orWhere('is_public', true);
                })
                ->firstOrFail();

            $newTemplate = $template->replicate();
            $newTemplate->name = $template->name . ' (Copy)';
            $newTemplate->user_id = auth()->id();
            $newTemplate->is_public = false;
            $newTemplate->save();

            return response()->json([
                'success' => true,
                'message' => 'Template cloned successfully',
                'data' => $newTemplate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clone template'
            ], 500);
        }
    }

    /**
     * Générer une miniature
     */
    private function generateThumbnail($html)
    {
        // Pour le moment, retourner une image placeholder
        // En production, vous pourriez utiliser un service de capture d'écran
        return 'https://via.placeholder.com/300x200/8b5cf6/ffffff?text=' . urlencode(substr(strip_tags($html), 0, 20));
    }
}
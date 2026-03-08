<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EditorController extends Controller
{
    /**
     * Afficher l'éditeur principal
     */
    public function index()
    {
        return view('editor');
    }

    /**
     * Afficher l'éditeur avec IA
     */
    public function aiEditor()
    {
        return view('editor-ai');
    }
}
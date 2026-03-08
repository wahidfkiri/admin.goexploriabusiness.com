<?php

namespace Vendor\Editor\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EditorController extends Controller
{
    /**
     * Afficher l'éditeur principal
     */
    public function index()
    {
        return view('editor::index');
    }
}
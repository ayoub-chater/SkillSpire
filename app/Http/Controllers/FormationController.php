<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formation;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::all();
        return response()->json($formations);
    }
}

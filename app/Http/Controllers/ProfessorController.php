<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Professor;
use Illuminate\Support\Facades\Validator;

class ProfessorController extends Controller
{
    public function formationsForProfessors($id)
    {
        $Professor = Professor::findOrFail($id);
        $formations = $Professor->formations;
        return response()->json($formations);
    }
}

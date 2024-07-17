<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscription;
use App\Models\Formation;
use Illuminate\Support\Facades\Validator;

class InscriptionController extends Controller
{
    public function index()
    {
        $formations = Inscription::all();
        return response()->json($formations);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'formation_id' => 'required|exists:formations,id',
            'status' => 'required|string|max:255',
            'payment_proof' => 'required|string|max:255',
            'justification' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $centre = Inscription::create($validator->validated());

        return response()->json(['message' => 'Inscription created successfully' , 'inscription' => $centre]);
    }

    public function getUsersByFormation($formationId)
    {
        $formation = Formation::findOrFail($formationId);

        $users = $formation->users()->withCount('inscriptions')->get();

        return response()->json($users);
    }

}

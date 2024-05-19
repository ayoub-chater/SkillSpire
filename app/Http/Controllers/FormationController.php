<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formation;
use Illuminate\Support\Facades\Validator;

class FormationController extends Controller
{
    public function index()
    {
        $formations = Formation::all();
        return response()->json($formations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image_path' => 'required|string|max:255',
            'professor_id' => 'required|exists:professors,id',
            'centre_id' => 'required|exists:centres,id',
            'salle_id' => 'required|exists:salles,id',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $centre = Formation::create($validator->validated());

        return response()->json(['message' => 'Formation created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'image_path' => 'required|string|max:255',
            'professor_id' => 'required|exists:professors,id',
            'centre_id' => 'required|exists:centres,id',
            'salle_id' => 'required|exists:salles,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $centre = Formation::findOrFail($id);

        $centre->update($validator->validated());

        return response()->json(['message' => 'Formation updated successfully']);
    }

    public function destroy($id)
    {
        $formation = Formation::findOrFail($id);
        $formation->delete();
        return response()->json(['message' => 'Formation deleted successfully']);
    }

}

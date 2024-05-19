<?php

namespace App\Http\Controllers;
use App\Models\Salle;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class SalleController extends Controller
{

    public function index()
    {
        $salles = Salle::all();
        return response()->json($salles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:30',
            'centre_id' => 'required|exists:centres,id',
            'admin_id' => 'required|exists:admins,id',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $salle = Salle::create($validator->validated());

        return response()->json(['message' => 'Salle created successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:30',
            'centre_id' => 'required|exists:centres,id',
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $salle = Salle::findOrFail($id);

        $salle->update($validator->validated());

        return response()->json(['message' => 'Salle updated successfully']);
    }

    public function destroy($id)
    {
        $salle = Salle::findOrFail($id);
        $salle->delete();
        return response()->json(['message' => 'Salle deleted successfully']);
    }

}

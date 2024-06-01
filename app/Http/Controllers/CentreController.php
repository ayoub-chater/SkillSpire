<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Centre;
use Illuminate\Support\Facades\Validator;

class CentreController extends Controller
{
    public function index()
    {
        $centres = Centre::all();
        return response()->json($centres);
    }

    
    public function fetchFormationsByCentre($centreId)
    {
        $centre = Centre::findOrFail($centreId);

        $formations = $centre->formations()->get();

        return response()->json($formations);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $centre = Centre::create($validator->validated());

        return response()->json(['message' => 'Centre created successfully']);
    }



    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $centre = Centre::findOrFail($id);

        $centre->update($validator->validated());

        return response()->json(['message' => 'Centre updated successfully']);
    }



    public function destroy($id)
    {
        $centre = Centre::findOrFail($id);
        $centre->delete();
        return response()->json(['message' => 'Centre deleted successfully']);
    }



    public function roomsFormationsCount()
    {
        $centres = Centre::withCount('salles')->withCount('formations')->get();
        return response()->json($centres);
    }

}

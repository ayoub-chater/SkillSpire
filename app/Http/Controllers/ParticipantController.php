<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Participant;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{

    public function historiques($id)
    {
        $participant = Participant::findOrFail($id);
        $inscriptions = $participant->inscriptions;
        return response()->json($inscriptions);
    }
}

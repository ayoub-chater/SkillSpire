<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\CentreController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\InscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// Formations
Route::get('/formations', [FormationController::class, 'index']);
Route::get('/formations/{id}', [FormationController::class, 'show']);
Route::post('/formations', [FormationController::class, 'store']);
Route::get('/participants/{participant}', [FormationController::class, 'getFormationsByParticipant']);
Route::get('/centres/{centreId}/formations', [CentreController::class, 'fetchFormationsByCentre']);




// Centres
Route::get('/centres', [CentreController::class, 'index']);
Route::post('/centres', [CentreController::class, 'store']);
Route::put('/centres/{id}', [CentreController::class, 'update']);
Route::delete('/centres/{id}', [CentreController::class, 'destroy']);
Route::get('/centres/roomsFormationsCount', [CentreController::class, 'roomsFormationsCount']);



// Salles
Route::get('/salles', [SalleController::class, 'index']);
Route::post('/salles', [SalleController::class, 'store']);
Route::put('/salles/{id}', [SalleController::class, 'update']);
Route::delete('/salles/{id}', [SalleController::class, 'destroy']);



// Users
Route::get('/users/{role}', [UserController::class, 'usersByRole']);
Route::get('/users/{role}/{id}', [UserController::class, 'show']);
Route::delete('/users/{role}/{id}', [UserController::class, 'destroy']);
Route::post('/users/{role}', [UserController::class, 'store']);



// Inscription
Route::get('/inscriptions', [InscriptionController::class, 'index']);
Route::post('/inscriptions', [InscriptionController::class, 'store']);



// Participants
Route::get('/historiques/{id}', [ParticipantController::class, 'historiques']);



// Professors
Route::get('/formationsProf/{id}', [ProfessorController::class, 'formationsForProfessors']);



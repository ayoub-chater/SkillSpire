<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Professor;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function usersByRole($role)
    {
        $validRoles = ['admins', 'participants', 'professors'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }
        $users = User::whereHas($role)->get();
        return response()->json($users);
    }


    public function show($role, $id)
    {
        $validRoles = ['admins', 'participants', 'professors'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }
        $user = User::whereHas($role)->findOrFail($id);
        return response()->json($user);
    }


    public function store(Request $request, $role)
    {
        $validRoles = ['admins', 'professors', 'participants'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $validatorRules = $this->getValidationRulesForRole($role);

        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a new user in the User table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create corresponding entries in the appropriate role tables
        switch ($role) {
            case 'admins':
                Admin::create(['user_id' => $user->id]);
                break;
            case 'professors':
                Professor::create([
                    'user_id' => $user->id,
                    'expertise' => $request->expertise,
                    'qualification' => $request->qualification,
                ]);
                break;
            case 'participants':
                Participant::create(['user_id' => $user->id]);
                break;
        }

        return response()->json($user, 201);
    }



    private function getValidationRulesForRole($role)
    {
        $commonRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];

        switch ($role) {
            case 'admins':
            case 'participants':
                return $commonRules;
            case 'professors':
                return array_merge($commonRules, [
                    'expertise' => 'required|string',
                    'qualification' => 'required|string',
                ]);
            default:
                return [];
        }
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if ($user->professor) {
            $professor = $user->professor;
            $professor->expertise = $request->expertise;
            $professor->qualification = $request->qualification;
            $professor->save();
        }

        return response()->json(['message' => 'User updated successfully']);
    }


    public function destroy($role, $id)
    {
        $validRoles = ['admins', 'participants', 'professors'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }
        $user = User::whereHas($role)->findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}


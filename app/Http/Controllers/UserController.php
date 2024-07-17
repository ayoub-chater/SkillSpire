<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Professor;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function usersByRole($role)
    {
        $validRoles = ['admins', 'participants', 'professors'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $users = User::whereHas($role)->get();

        if ($role === 'professors') {
            $users->load('professors');
        }

        return response()->json($users);
    }


    public function show($role, $id)
    {
        $validRoles = ['admins', 'participants', 'professors'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }

        $user = User::whereHas($role)->with($role)->findOrFail($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userRoleInfo = $user->only(['id', 'name', 'email', 'created_at', 'updated_at']);

        if ($role === 'participants') {
            $userRoleInfo['role'] = 'participants';
            $userRoleInfo['participant_info'] = $user->participants;
        } elseif ($role === 'admin') {
            $userRoleInfo['role'] = 'admin';
            $userRoleInfo['admin_info'] = $user->admin;
        } elseif ($role === 'professor') {
            $userRoleInfo['role'] = 'professor';
            $userRoleInfo['professor_info'] = $user->professor;
        }

        return response()->json($userRoleInfo);
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        switch ($role) {
            case 'admins':
                Admin::create(['user_id' => $user->id]);
                break;
            case 'professors':
                Professor::create([
                    'user_id' => $user->id,
                    'image_path' => $request->image_path,
                    'expertise' => $request->expertise,
                    'qualification' => $request->qualification,
                ]);
                break;
            case 'participants':
                $part = Participant::create(['user_id' => $user->id]);
                break;
        }

        return response()->json(['user' =>$user , 'participant' => $part], 200);
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
        $validRoles = ['admin', 'participant', 'professor'];
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 400);
        }
        $user = User::whereHas("{$role}s")->findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}


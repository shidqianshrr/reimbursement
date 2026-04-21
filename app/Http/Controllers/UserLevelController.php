<?php

namespace App\Http\Controllers;

use App\Models\AdminLevel;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Http\Request;

class UserLevelController extends Controller
{
    public function addUsertoAdmin(Request $request)
    {
        $exist = UserLevel::where('user_id', $request->user_id)->where('admin_level_id', $request->role_id)->first();
        if ($exist) {
            return response()->json(['status' => 400, 'message' => 'User sudah mempunyai role tersebut']);
        }

        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan']);
        }

        $role = AdminLevel::find($request->role_id);
        if (!$role) {
            return response()->json(['status' => 404, 'message' => 'Role tidak ditemukan']);
        }

        $data = new UserLevel([
            'user_id' => $user->id,
            'admin_level_id' => $role->id
        ]);

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses memberikan user sebuah role',
                'result' => $data
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Failed'
        ], 400);
    }

    public function getlistUserAdmin()
    {
        $data = UserLevel::with(['user', 'role'])->get();

        return response()->json([
            'status' => 200,
            'result' => $data
        ]);
    }

    public function updateRoleuserAdmin(Request $request, $id)
    {
        $data = UserLevel::find($id);
        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $role = AdminLevel::find($request->role_id);
        if (!$role) {
            return response()->json([
                'status' => 404,
                'message' => 'Role tidak ditemukan'
            ]);
        }

        // $data->admin_level_id = $request->admin_level_id ?? $data->admin_level_id;
        $data->admin_level_id = $request->role_id ?? $data->admin_level_id;
        // $data->save();

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses update role user',
                'result' => $data
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Failed'
        ], 400);
    }
}

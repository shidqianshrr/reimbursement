<?php

namespace App\Http\Controllers;

use App\Models\AdminLevel;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    public function AddadminUser(Request $request)
    {
        $role_user = $request->input('role_user');
        $description = $request->input('description');

        $data = new AdminLevel([
            'role_user' => $role_user,
            'description' => $description,
        ]);

        if ($data->save()) {
            return response()->json(['status' => 200, 'message' => 'Sukses menambahkan Admin User']);
        } else
            return response()->json(['status' => 400, 'message' => 'Gagal menambahkan Admin User']);
    }

    public function getAdminUser()
    {
        $data = AdminLevel::select('*')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'result' => $data,
        ]);
    }

    public function updateAdminUser(Request $request, $level_id)
    {
        $role = AdminLevel::find($level_id);
        if (!$role) {
            return response()->json(['status' => 404, 'message' => 'Role admin tidak ditemukan']);
        }

        $role->role_user = $request->role_user ?? $role->role_user;
        $role->description = $request->description ?? $role->description;

        if ($role->update()) {
            return response()->json(['status' => 200, 'message' => 'Sukses update Admin User']);
        } else
            return response()->json(['status' => 400, 'message' => 'Gagal update Admin User']);
    }
}

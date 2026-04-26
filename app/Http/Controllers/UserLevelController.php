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
        $exist = UserLevel::where('user_id', $request->user_id)
            ->where('admin_level_id', $request->role_id)
            ->first();

        if ($exist) {
            return response()->json(['status' => 400, 'message' => 'User sudah mempunyai role tersebut'], 400);
        }

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan'], 404);
        }

        $admin = AdminLevel::where('id', $request->role_id)->first();
        if (!$admin) {
            return response()->json(['status' => 404, 'message' => 'Admin level tidak ditemukan'], 404);
        }

        $data = UserLevel::create([
            'user_id' => $request->user_id,
            'admin_level_id' => $request->role_id
        ]);

        if ($data) {
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

    public function getlistUserAdmin(Request $request)
    {
        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;

        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }

        $sort = json_decode(urldecode($request->input('sort')));

        $query = UserLevel::with(['user', 'role']);

        // Filter
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        // Sort
        if ($sort) {
            foreach ($sort as $key => $value) {
                $query->orderBy($key, $value);
            }
        }

        // Pagination
        if ($limit > 0) {
            $query->limit($limit)->offset($offset);
        }

        $data = $query->get();

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
            ], 404);
        }

        if ($request->filled('role_id')) {
            $admin = AdminLevel::where('id', $request->role_id)->first();
            if (!$admin) {
                return response()->json(['status' => 404, 'message' => 'Admin level tidak ditemukan'], 404);
            }
            $data->admin_level_id = $request->role_id;
        }

        if ($request->filled('user_id')) {
            $user = User::where('id', $request->user_id)->first();
            if (!$user) {
                return response()->json(['status' => 404, 'message' => 'User tidak ditemukan'], 404);
            }
            $data->user_id = $request->user_id;
        }

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

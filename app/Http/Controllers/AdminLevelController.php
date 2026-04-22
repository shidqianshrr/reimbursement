<?php

namespace App\Http\Controllers;

use App\Models\AdminLevel;
use Illuminate\Http\Request;

class AdminLevelController extends Controller
{
    public function AddadminUser(Request $request)
    {
        $data = AdminLevel::create([
            'role_user' => $request->role_user,
            'description' => $request->description,
        ]);

        if ($data) {
            return response()->json(['status' => 200, 'message' => 'Sukses menambahkan Admin User', 'result' => $data]);
        }

        return response()->json(['status' => 400, 'message' => 'Gagal menambahkan Admin User'], 400);
    }

    public function getAdminUser(Request $request)
    {
        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;
        
        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }
        
        $sort = json_decode(urldecode($request->input('sort')));

        $query = AdminLevel::query();

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
            'message' => 'Success',
            'result' => $data,
        ]);
    }

    public function updateAdminUser(Request $request, $level_id)
    {
        $role = AdminLevel::find($level_id);
        if (!$role) {
            return response()->json(['status' => 404, 'message' => 'Role admin tidak ditemukan'], 404);
        }

        $role->role_user = $request->role_user ?? $role->role_user;
        $role->description = $request->description ?? $role->description;

        if ($role->save()) {
            return response()->json(['status' => 200, 'message' => 'Sukses update Admin User', 'result' => $role]);
        }

        return response()->json(['status' => 400, 'message' => 'Gagal update Admin User'], 400);
    }
}

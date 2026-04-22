<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategories(Request $request)
    {
        $data = Category::create([
            'name' => $request->name
        ]);

        if ($data) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses menambahkan category',
                'result' => $data
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Failed'
        ], 400);
    }

    public function getCategory(Request $request)
    {
        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;
        
        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }
        
        $sort = json_decode(urldecode($request->input('sort')));

        $query = Category::query();

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

    public function updateCategory(Request $request, $category_id)
    {
        $data = Category::find($category_id);
        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->name = $request->name ?? $data->name;

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Sukses update category',
                'result' => $data
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Failed'
        ], 400);
    }
}

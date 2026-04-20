<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategories(Request $request)
    {
        $data = new Category([
            'name' => $request->name,
        ]);

        if ($data->save()) {
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

    public function getCategory()
    {
        $data = Category::select('*')->get();

        return response()->json([
            'status' => 200,
            'result' => $data
        ]);
    }

    public function updateCategory(Request $request, $category_id)
    {
        $data = Category::where('id', $category_id)->first();
        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        // $data->fill($request->only(['name']));
        $data->name = $request->name ?? $data->name;
        $data->save();

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

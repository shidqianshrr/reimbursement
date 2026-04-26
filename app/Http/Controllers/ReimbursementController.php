<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Reimbursement;
use App\Models\UserLevel;
use App\Services\ReimbursementService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReimbursementController extends Controller
{
    protected $service;

    public function __construct(ReimbursementService $service)
    {
        $this->service = $service;
    }

    public function reqReimburse(Request $request)
    {

        // $category = Category::find($request->category_id);
        // if (!$category) {
        //     return response()->json([
        //         'status' => 404,
        //         'message' => 'Kategori tidak ditemukan'
        //     ], 404);
        // }

        // $data = new Reimbursement([
        //     'title' => $request->title,
        //     'description' => $request->description,
        //     'amount' => $request->amount,
        //     'category_id' => $category->id,
        //     'merchant' => $request->merchant,
        //     'payment_method' => $request->payment_method,
        //     'invoice_number' => $request->invoice_number,
        //     'project' => $request->project,
        //     'submitted_at' => now(),
        //     'submitted_by' => $request->user()->id,
        //     'division' => $request->user()->division,
        // ]);

        $result = $this->service->createReimbursement(
            $request->user(),
            // $request->all()
            $request->only([
                'title',
                'description',
                'amount',
                'category_id',
                'merchant',
                'payment_method',
                'invoice_number',
                'project'
            ])
        );

        // if ($data->save()) {
        //     return response()->json([
        //         'status' => 200,
        //         'message' => 'Reimbursement submitted',
        //         'data' => $data
        //     ]);
        // }

        if (!$result['status']) {
            return response()->json([
                'status' => 400,
                'message' => $result['message']
            ], 400);
        }

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Reimbursement submitted',
        //     'data' => $result['data']
        // ]);

        return response()->json([
            'message' => 'Reimbursement submitted',
            'data' => $result['data']
        ], 200);
    }
    public function getReimburse(Request $request)
    {
        $admin = UserLevel::where('user_id', $request->user()->id)
            ->where('admin_level_id', 2)
            ->first();

        if (!$admin) {
            return response()->json([
                'status' => 403,
                'message' => 'Unauthorized'
            ], 403);
        }

        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;

        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }

        $sort = json_decode(urldecode($request->input('sort')));

        $query = Reimbursement::with(['category', 'user']);

        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        if ($sort) {
            foreach ($sort as $key => $value) {
                $query->orderBy($key, $value);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($limit > 0) {
            $query->limit($limit)->offset($offset);
        }

        $data = $query->get();

        return response()->json([
            'status' => 200,
            'message' => 'Success (Admin View)',
            'result' => $data
        ]);
    }

    public function getMyReimburse(Request $request)
    {
        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;

        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }

        $sort = json_decode(urldecode($request->input('sort')));

        $query = Reimbursement::with(['category'])->where('submitted_by', $request->user()->id);

        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        if ($sort) {
            foreach ($sort as $key => $value) {
                $query->orderBy($key, $value);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if ($limit > 0) {
            $query->limit($limit)->offset($offset);
        }

        $data = $query->get();

        return response()->json([
            'status' => 200,
            'message' => 'Success (User View)',
            'result' => $data
        ]);
    }

    public function updateReimburse(Request $request, $reimburse_id)
    {
        $data = Reimbursement::find($reimburse_id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Reimbursement tidak ditemukan'
            ], 404);
        }

        if ($data->submitted_by != $request->user()->id) {
            return response()->json([
                'status' => 403,
                'message' => 'Tidak boleh edit data orang lain'
            ], 403);
        }

        if ($data->status !== 'pending') {
            return response()->json([
                'status' => 400,
                'message' => 'Data sudah diproses, tidak bisa diubah'
            ], 400);
        }

        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);

            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }
        }

        if ($data->update($request->only([
            'title',
            'description',
            'amount',
            'category_id',
            'merchant',
            'payment_method',
            'invoice_number',
            'project'
        ]))) {
            return response()->json([
                'status' => 200,
                'message' => 'Reimbursement updated',
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Gagal update reimbursement'
        ], 400);
    }

    public function ReimburseSpec($reimburse_id)
    {
        $data = Reimbursement::with(['category', 'user'])->find($reimburse_id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'result' => $data
        ]);
    }

    public function approveReimburse(Request $request, $reimburse_id)
    {
        $data = Reimbursement::find($reimburse_id);

        if (!$data) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan'], 404);
        }

        if ($data->submitted_by == $request->user()->id) {
            return response()->json([
                'status' => 403,
                'message' => 'Tidak boleh approve reimbursement sendiri'
            ], 403);
        }

        $admin = UserLevel::where('user_id', $request->user()->id)
            ->where('admin_level_id', 2)
            ->first();

        if (!$admin) {
            return response()->json([
                'status' => 403,
                'message' => 'Anda tidak memiliki akses untuk approve reimbursement'
            ], 403);
        }

        $data->status = $request->status;
        $data->approved_by = $request->user()->id;
        $data->approved_at = now();

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Status reimbursement diperbarui menjadi ' . $request->status,
                'result' => $data
            ]);
        }

        return response()->json(['status' => 400, 'message' => 'Gagal memperbarui status'], 400);
    }
}

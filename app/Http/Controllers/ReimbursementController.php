<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReimbursementController extends Controller
{
    public function reqReimburse(Request $request)
    {
        $data = new Reimbursement([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'merchant' => $request->merchant,
            'payment_method' => $request->payment_method,
            'invoice_number' => $request->invoice_number,
            'project' => $request->project,
            'submitted_at' => now(),
            'submitted_by' => $request->user()->id,
            'division' => $request->user()->division,
        ]);

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Reimbursement submitted',
                'data' => $data
            ]);
        }

        return response()->json(['status' => 400, 'message' => 'Gagal mengajuan reimburse'], 400);
    }
    public function getReimburse(Request $request)
    {
        $limit = ($request->input('limit') != null) ? $request->input('limit') : 0;
        $offset = ($request->input('offset') != null) ? $request->input('offset') : 0;
        
        $filter = json_decode($request->input('filter'));
        if ($filter == null) {
            $filter = json_decode(urldecode($request->input('filter')));
        }
        
        $sort = json_decode(urldecode($request->input('sort')));

        $query = Reimbursement::with(['category', 'user']);

        // Logika Filter
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        // Logika Sort
        if ($sort) {
            foreach ($sort as $key => $value) {
                $query->orderBy($key, $value);
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Default sort
        }

        // Logika Pagination
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

        // Logika Filter
        if ($filter) {
            foreach ($filter as $key => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($key, $value);
                }
            }
        }

        // Logika Sort
        if ($sort) {
            foreach ($sort as $key => $value) {
                $query->orderBy($key, $value);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Logika Pagination
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

        $data->title = $request->title ?? $data->title;
        $data->description = $request->description ?? $data->description;
        $data->amount = $request->amount ?? $data->amount;
        $data->category_id = $request->category_id ?? $data->category_id;
        $data->merchant = $request->merchant ?? $data->merchant;
        $data->payment_method = $request->payment_method ?? $data->payment_method;
        $data->invoice_number = $request->invoice_number ?? $data->invoice_number;
        $data->project = $request->project ?? $data->project;

        if ($data->save()) {
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

    // 1. Approve / Reject Reimburse
    public function approveReimburse(Request $request, $reimburse_id)
    {
        $data = Reimbursement::find($reimburse_id);

        if (!$data) {
            return response()->json(['status' => 404, 'message' => 'Data tidak ditemukan'], 404);
        }

        $data->status = $request->status; // 'approved' atau 'rejected'
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

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

        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $category_id = $request->input('category_id');
        $submitted_at = now();
        $submitted_by = $request->user()->id;
        $division = $request->user()->division;
        $merchant = $request->input('merchant');
        $payment_method = $request->input('payment_method');
        $invoice_number = $request->input('invoice_number');
        $project = $request->input('project');

        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan']);
        }

        $data = new Reimbursement([
            'title' => $title,
            'description' => $description,
            'amount' => $amount,
            'category_id' => $category_id,
            'submitted_at' => $submitted_at,
            'submitted_by' => $submitted_by,
            'division' => $division,
            'merchant' => $merchant,
            'payment_method' => $payment_method,
            'invoice_number' => $invoice_number,
            'project' => $project,
        ]);

        if ($data->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Reimbursement submitted',
                'data' => $data
            ]);
        } else
            return response()->json(['status' => 400, 'message' => 'Gagal mengajuan reimburse']);
    }

    public function getReimburse()
    {
        // $data = UserLevel::with(['user', 'role'])->get();

        // $data = Reimbursement::select('*')->get();
        $data = Reimbursement::with(['category', 'user'])->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
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
            ]);
        }

        $category_id = $request->input('category_id');
        if ($category_id) {
            $category = Category::find($category_id);
            if (!$category) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Kategori tidak ditemukan'
                ]);
            }
        }

        $data->title = $request->input('title') ?? $data->title;
        $data->description = $request->input('description') ?? $data->description;
        $data->amount = $request->input('amount') ?? $data->amount;
        $data->category_id = $category_id ?? $data->category_id;
        $data->merchant = $request->input('merchant') ?? $data->merchant;
        $data->payment_method = $request->input('payment_method') ?? $data->payment_method;
        $data->invoice_number = $request->input('invoice_number') ?? $data->invoice_number;
        $data->project = $request->input('project') ?? $data->project;

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
        ]);
    }

    public function ReimburseSpec($reimburse_id)
    {
        $data = Reimbursement::where('id', $reimburse_id)->first();
        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'result' => $data
        ]);
    }
}

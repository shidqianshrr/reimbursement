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
        // $submitted_by = $request->user()->id;
        // $submitted_at = Carbon::now()->format('Y-m-d H:i:s');;

        // return $submitted_at;

        // $reimburse = Reimbursement::

        // $category = Category::find($request->input('category_id'));
        // if (!$category) {
        //     return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan']);
        // }

        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $category_id = $request->input('category_id');
        $user_id = $request->user()->id;
        // $status = $request->input('status');
        $submitted_at = now();
        // $approved_at = Carbon::now()->format('Y-m-d H:i:s');
        // $approved_by = $request->input('approved_by');
        $submitted_by = $request->user()->id;
        // $deleted_by = $request->input('deleted_by');
        $division = $request->user()->division;
        $merchant = $request->input('merchant');
        // $attachment = $request->input('attachment');
        $payment_method = $request->input('payment_method');
        $invoice_number = $request->input('invoice_number');
        // $updated_by = $request->user()->id;
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
            'user_id' => $user_id,
            // 'status' => $status,
            'submitted_at' => $submitted_at,
            // 'approved_at' => $approved_at,
            // 'approved_by' => $approved_by,
            'submitted_by' => $submitted_by,
            // 'deleted_by' => $deleted_by,
            'division' => $division,
            'merchant' => $merchant,
            // 'attachment' => $attachment,
            'payment_method' => $payment_method,
            'invoice_number' => $invoice_number,
            // 'updated_by' => $updated_by,
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

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Reimbursement submitted',
        //     'data' => $data
        // ]);
    }

    public function getReimburse()
    {
        return response()->json([
            'status' => 200,
            'message' => 'Success'
        ]);
    }
}

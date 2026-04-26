<?php

namespace App\Services;

use App\Models\Reimbursement;
use App\Models\Category;


class ReimbursementService
{
    public function createReimbursement($user, $data)
    {
        $category = Category::find($data['category_id']);
        if (!$category) {
            return ['status' => false, 'message' => 'Kategori tidak ditemukan'];
        }

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

        $reimburse = Reimbursement::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'amount' => $data['amount'],
            'category_id' => $category->id,
            'merchant' => $data['merchant'],
            'payment_method' => $data['payment_method'],
            'invoice_number' => $data['invoice_number'],
            'project' => $data['project'],
            'submitted_at' => now(),
            'submitted_by' => $user->id,
            'division' => $user->division,
        ]);

        return [
            'status' => true,
            'data' => $reimburse
        ];
    }
}

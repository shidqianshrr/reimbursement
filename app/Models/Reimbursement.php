<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $table = 'reimbursements';
    protected $fillable = [
        'title',
        'description',
        'amount',
        'category_id',
        'user_id',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'submitted_by',
        'deleted_by',
        'division',
        'merchant',
        'attachment',
        'payment_method',
        'invoice_number',
        'updated_by',
        'project',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}

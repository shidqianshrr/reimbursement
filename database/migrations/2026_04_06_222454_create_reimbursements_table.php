<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);

            $table->bigInteger('category_id');
            // $table->bigInteger('user_id');

            $table->string('status')->default('pending');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->bigInteger('approved_by')->nullable();
            $table->bigInteger('submitted_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->string('division')->nullable();
            $table->string('merchant')->nullable();

            $table->string('attachment')->nullable();

            $table->string('payment_method')->nullable();
            $table->string('invoice_number');

            $table->bigInteger('updated_by')->nullable();

            $table->string('project')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reimbursements');
    }
};

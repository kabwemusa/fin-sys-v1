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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('loan_product_id')->constrained();
            $table->decimal('amount_requested', 12, 2);
            $table->integer('tenure_months');
            $table->text('purpose')->nullable();
            $table->enum('status', [
                'pending',
                'under_review',
                'info_requested',
                'approved',
                'rejected',
                'disbursed',
                'closed',
                'defaulted',
            ])->default('pending');
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('amount_approved', 12, 2)->nullable();
            $table->decimal('monthly_repayment', 12, 2)->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('info_requested_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};

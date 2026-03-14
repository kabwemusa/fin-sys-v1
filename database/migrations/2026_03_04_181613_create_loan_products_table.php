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
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['salary_backed', 'collateral_backed']);
            $table->text('description');
            $table->decimal('min_amount', 12, 2);
            $table->decimal('max_amount', 12, 2);
            $table->integer('min_tenure_months');
            $table->integer('max_tenure_months');
            $table->decimal('interest_rate', 5, 2)->comment('Monthly percentage rate');
            $table->boolean('requires_collateral')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_products');
    }
};

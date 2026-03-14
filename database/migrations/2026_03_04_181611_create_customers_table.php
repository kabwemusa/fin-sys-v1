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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nrc_number', 20);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->text('residential_address');
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('employer_name', 255)->nullable();
            $table->text('employer_address')->nullable();
            $table->string('job_title', 255)->nullable();
            $table->date('employment_date')->nullable();
            $table->decimal('monthly_income', 12, 2)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('status', 40)->default('pending')->after('file_size');
            $table->foreignId('reviewed_by')->nullable()->after('verified_by')->constrained('users');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_notes')->nullable()->after('notes');
        });

        DB::table('documents')
            ->where('is_verified', true)
            ->update([
                'status' => 'approved',
                'reviewed_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reviewed_by');
            $table->dropColumn(['status', 'reviewed_at', 'review_notes']);
        });
    }
};

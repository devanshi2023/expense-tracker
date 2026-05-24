<?php

use App\Models\ExpenseClaim;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            // DECIMAL(12,2) stores currency exactly to two decimal places.
            $table->decimal('amount', 12, 2);
            $table->date('claim_date');
            $table->text('description');
            $table->text('receipt_note')->nullable();
            $table->string('status')->default(ExpenseClaim::STATUS_PENDING);
            $table->text('manager_comment')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'status', 'claim_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};

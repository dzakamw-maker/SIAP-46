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
        Schema::create('stamp_duty_records', function (Blueprint $table) {
            $table->id();
            $table->date('record_date');
            $table->integer('quantity_sold')->default(0);
            $table->integer('quantity_purchased')->default(0);
            $table->integer('remaining_stock');
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_duty_records');
    }
};

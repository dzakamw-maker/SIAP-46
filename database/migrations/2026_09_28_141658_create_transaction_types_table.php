<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $types = [
            'Setor Tunai BNI', 'Setor Tunai Antar Bank', 'PULSA- INDOSAT', 'PULSA-TSEL',
            'PULSA-XL', 'PULSA- 3', 'PLN- PREPAID', 'PLN', 'BPJS', 'TOPUP- GOPAY',
            'TOPUP- SPAY', 'TOPUP-DANA', 'TELKOM', 'PDAM', 'Tarik Tunai', 'Tagihan Telepon', 'Materai',
        ];

        $now = now();
        $typeData = array_map(function ($t) use ($now) {
            return [
                'code' => Str::slug($t, '_'),
                'name' => $t,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $types);

        DB::table('transaction_types')->insert($typeData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }
};

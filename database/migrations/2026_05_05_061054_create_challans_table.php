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
        Schema::create('challans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prahari_id')->nullable()->constrained('praharis')->onDelete('set null');
            $table->foreignId('case_id')->nullable()->constrained('cases')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('case_categories')->onDelete('set null');
            $table->string('vehicle_number')->nullable();
            $table->decimal('fine_amount', 10, 2)->nullable();
            $table->enum('status', ['paid','cancelled','pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challans');
    }
};

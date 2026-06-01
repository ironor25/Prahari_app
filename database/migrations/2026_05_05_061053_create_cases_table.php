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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prahari_id')->nullable()->constrained('praharis')->onDelete('set null');
            $table->foreignId('case_category_id')->nullable()->constrained('case_categories')->onDelete('set null');
            $table->string('vehicle_number')->nullable();
            $table->string('location')->nullable();
            $table->string('evidence')->nullable();
            $table->enum('status', ['Open', 'Approved','Rejected'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};

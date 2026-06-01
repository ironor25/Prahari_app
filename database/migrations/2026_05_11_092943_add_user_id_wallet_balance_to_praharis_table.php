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
        Schema::table('praharis', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('bank_account');
        });
    }
   
    public function down(): void
    {
        Schema::table('praharis', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('wallet_balance');
        });
    }
};

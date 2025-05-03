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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('description');
            $table->unsignedBigInteger('supplier_id')->nullable()->after('client_id');

            $table->foreign('client_id')->references('id')->on('companies')->nullOnDelete();
            $table->foreign('supplier_id')->references('id')->on('companies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['client_id', 'supplier_id']);
        });
    }
};

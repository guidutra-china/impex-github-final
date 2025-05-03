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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('client_company_id')->constrained('companies');
            $table->foreignId('supplier_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->date('order_date');
            $table->string('origen')->nullable();
            $table->string('destination')->nullable();
            $table->string('client_number')->nullable();
            $table->string('supplier_number')->nullable();
            $table->bigInteger('total_price')->default(0)->nullable();
            $table->bigInteger('discount')->default(0)->nullable();
            $table->bigInteger('net_weight')->default(0)->nullable();
            $table->bigInteger('gross_weight')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

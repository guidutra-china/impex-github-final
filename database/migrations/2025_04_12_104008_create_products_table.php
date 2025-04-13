<?php

use App\Models\Family;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku_client')->unique()->nullable();
            $table->string('sku_supplier')->unique()->nullable();
            $table->string('hscode')->nullable();
            $table->string('ncm')->nullable();
            $table->bigInteger('cost')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('currency')->nullable();
            $table->foreignIdFor(Family::class)->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

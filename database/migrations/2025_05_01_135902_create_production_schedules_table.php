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
        Schema::create("production_schedules", function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_item_id")->constrained()->cascadeOnDelete(); // Link to the specific order item
            $table->date("scheduled_date");
            $table->integer("quantity_scheduled");
            $table->timestamps();

            // Optional: Add unique constraint if needed (e.g., only one schedule entry per item per day)
            // $table->unique(["order_item_id", "scheduled_date"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_schedules');
    }
};

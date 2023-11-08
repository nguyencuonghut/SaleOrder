<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->bigInteger('schedule_id')->unsigned();
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->bigInteger('creator_id')->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('level1_manager_id')->nullable()->unsigned();//Giám đốc
            $table->foreign('level1_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('level2_manager_id')->nullable()->unsigned();//Trưởng vùng - Giám sát
            $table->foreign('level2_manager_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('level1_manager_approved_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->enum('level2_manager_approved_result', ['Đồng ý', 'Từ chối'])->nullable();
            $table->enum('status', ['Chưa duyệt', 'TV/GS đã duyệt', 'Giám đốc đã duyệt']);
            $table->date('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('orders');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};

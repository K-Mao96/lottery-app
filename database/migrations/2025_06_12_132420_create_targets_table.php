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
        Schema::create('targets', function (Blueprint $table) {
            $table->id()->comment('抽選対象者ID');
            $table->foreignId('target_list_id')->constrained('target_lists')->onDelete('cascade')->comment('抽選対象者のリスト名'); // 外部キー制約
            $table->string('name')->comment('抽選対象者の氏名');
            $table->boolean('is_excluded')->default(false)->comment('抽選除外フラグ');
            $table->boolean('is_selected')->default(false)->comment('当選フラグ');
            $table->timestamp('selected_at')->comment('当選日時')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};

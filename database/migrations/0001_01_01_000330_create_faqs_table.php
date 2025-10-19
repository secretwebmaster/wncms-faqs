<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('faqs')) {
            Schema::create('faqs', function (Blueprint $table) {
                $table->id();
                $table->string('status')->default('active');
                $table->string('slug')->unique();
                $table->string('question');
                $table->text('answer')->nullable();
                $table->string('label')->nullable();
                $table->text('remark')->nullable();
                $table->integer('order')->nullable();
                $table->boolean('is_pinned')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};

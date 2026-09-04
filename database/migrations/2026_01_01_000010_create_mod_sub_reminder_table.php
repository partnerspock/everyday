<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mod_sub_reminder', function (Blueprint $table) {
            $table->id();
            $table->foreignid('id_spaces')->constrained('mod_spaces')->cascadeOnDelete();
            $table->string('row_title');
            $table->text('row_description')->nullable();
            $table->enum('row_status', ['ticked', 'not_ticked'])->default('not_ticked');
            $table->unsignedInteger('row_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mod_sub_reminder');
    }
};
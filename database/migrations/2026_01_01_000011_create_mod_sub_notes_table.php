<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mod_sub_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignid('id_spaces')->constrained('mod_spaces')->cascadeOnDelete();
            $table->string('title_notes');
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mod_sub_notes');
    }
};
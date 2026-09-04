<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mod_sub_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignid('id_spaces')->constrained('mod_spaces')->cascadeOnDelete();
            $table->string('pro_status')->default('not_started');
            $table->string('pro_title');
            $table->date('pro_start_at')->nullable();
            $table->date('pro_end_at')->nullable();
            $table->longText('pro_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mod_sub_projects');
    }
};
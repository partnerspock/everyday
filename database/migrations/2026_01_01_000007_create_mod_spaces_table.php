<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mod_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignid('created_user')->constrained('admins');
            $table->string('title_spaces');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mod_spaces');
    }
};
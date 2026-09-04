<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins_has_role', function (Blueprint $table) {
            $table->foreignId('id_user')->constrained('admins')->cascadeOnDelete();
            $table->foreignId('id_role')->constrained('admins_roles')->cascadeOnDelete();
            $table->primary(['id_user', 'id_role']);
            $table->boolean('is_default')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins_has_role');
    }
};
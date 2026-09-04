<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins_role_has_permission', function (Blueprint $table) {
            $table->foreignid('id_role')->constrained('admins_roles')->cascadeOnDelete();
            $table->foreignid('id_permission')->constrained('admins_permissions')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['id_role', 'id_permission']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins_role_has_permission');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission_name');
            $table->enum('permission_status', ['active', 'inactive'])->default('inactive');
            $table->boolean('is_default')->default(false);
            $table->enum('module_name', ['general_notes', 'spaces', 'admin_users','roles','permissions','activity_logs']);
            $table->string('module_key');
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins_permissions');
    }
};
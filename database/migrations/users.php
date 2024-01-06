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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->nullable();
            $table->text('password');
            $table->tinyInteger('is_active')->default(1);
            $table->text('verify_code')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
        Schema::dropIfExists('personal_access_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

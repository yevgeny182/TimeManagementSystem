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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();    // Add first name column
            $table->string('last_name')->nullable();     // Add last name column
            $table->string('email')->unique();           // Add email column, set as unique
            $table->string('phone_number')->nullable();  // Add phone number column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'email', 'phone_number']);  // Drop columns on rollback
        });
    }
};

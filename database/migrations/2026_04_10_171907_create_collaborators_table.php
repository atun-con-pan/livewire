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
        Schema::create('collaborators', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('first_surname');
            $table->string('second_last_name')->nullable();
            $table->string('dpi')->unique();
            $table->string('birthdate');
            $table->string('marital_status');
            $table->string('residence');
            $table->string('phone');
            $table->string('email')->nullable()->unique();
            $table->string('position');
            $table->string('start_date');
            $table->string('termination_date')->nullable();
            $table->string('salary')->nullable();
            $table->string('contract');
            $table->string('pattern');
            $table->string('bank_account')->nullable()->unique();
            $table->string('bank')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('no_igss')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborators');
    }
};

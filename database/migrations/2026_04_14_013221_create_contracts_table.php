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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('no_contract');
            $table->date('contract_registration_date');
            $table->date('contract_subscription_date');
            $table->date('start_date_activities');
            $table->date('final_date_activities')->nullable();
            $table->string('nog_contract')->unique();
            $table->string('contract_name');
            $table->string('execution_address');
            $table->string('number_workers');
            $table->string('salary_amount');
            $table->string('status');
            $table->string('filial');
            $table->string('person_charge');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

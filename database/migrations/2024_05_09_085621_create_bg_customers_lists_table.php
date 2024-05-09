<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bg_customers_lists', function (Blueprint $table) {
            $table->id();
            $table->string('customer_no');
            $table->string('cust_ac_no');
            $table->string('ccy')->default('KES');
            $table->string('branch');
            $table->string('customer_prefix')->nullable();
            $table->string('full_name');
            $table->string('date_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('minor')->default('NO');
            $table->string('customer_type');
            $table->string('account_class');
            $table->string('taxid_no')->nullable();
            $table->string('status')->default('ACTIVE');
            $table->string('national_id')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('address_line_4')->nullable();
            $table->string('country')->nullable();
            $table->string('nationality')->nullable();
            $table->string('passport_no')->nullable();
            $table->timestamp('pp_exp_date')->nullable();
            $table->string('nominee_name')->nullable();
            $table->timestamp('ac_open_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bg_customers_lists');
    }
};

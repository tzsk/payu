<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayuPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payu_payments', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payable_id')->default(0);
            $table->string('payable_type')->nullable();
            $table->string('txnid');
            $table->string('mihpayid');
            $table->string('firstname');
            $table->string('email');
            $table->string('phone');
            $table->double('amount');
            $table->double('discount');
            $table->double('net_amount_debit');
            $table->text('data');
            $table->string('status');
            $table->string('unmappedstatus');
            $table->string('mode');
            $table->string('bank_ref_num');
            $table->string('bankcode');
            $table->string('cardnum');
            $table->string('name_on_card');
            $table->string('issuing_bank');
            $table->string('card_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payu_payments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('transactions', function(Blueprint $table) {
            	$table->bigIncrements('id');
                $table->unsignedBigInteger('sender_id')->nullable();
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
                $table->unsignedBigInteger('receiver_id')->nullable();
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
                $table->double('transaction_amount', 30, 2,true)->nullable();
                $table->enum('status', ['pending', 'completed'])->nullable()->default('pending');
            	$table->timestamps();
            	$table->softDeletes();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

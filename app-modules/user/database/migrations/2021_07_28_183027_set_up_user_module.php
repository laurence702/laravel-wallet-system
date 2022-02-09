<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetUpUserModule extends Migration
{
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('wallet_id',12)->unique();
			$table->string('first_name', 50);
			$table->string('last_name', 50);
			$table->string('phone', 100)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('pin', 20);
			$table->string('pin_hash');
			$table->string('password')->nullable();
			$table->boolean('verified')->nullable()->default(0);
			$table->double('account_balance', 8, 2)->nullable()->default(00.000000);
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	
	public function down()
	{
		 Schema::dropIfExists('users');
	}
}

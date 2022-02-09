<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SetUpTransactionModule extends Migration
{
	public function up()
	{
		// Schema::create('transaction', function(Blueprint $table) {
		// 	$table->bigIncrements('id');
		// 	$table->timestamps();
		// 	$table->softDeletes();
		// });
	}
	
	public function down()
	{
		// Schema::dropIfExists('transaction');
	}
}

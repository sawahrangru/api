<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiTokensTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('api_tokens', function (Blueprint $table) {
   $table->bigIncrements('id');
   $table->longText('client_id');
   $table->longText('access_token');
   $table->string('token_type')->default('Bearer');
   $table->integer('expires_in')->default(3600);
   $table->string('scope')->default('resource.WRITE resource.READ');
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
  Schema::dropIfExists('api_tokens');
 }
}

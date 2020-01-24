<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiClientsTable extends Migration
{
 /**
  * Run the migrations.
  *
  * @return void
  */
 public function up()
 {
  Schema::create('api_clients', function (Blueprint $table) {
   $table->uuid('id');
   $table->primary('id');
   $table->unsignedBigInteger('user_id');
   $table->string('name')->unique();
   $table->text('description')->nullable();
   $table->string('secret')->nullable();
   $table->string('key')->nullable();
   $table->boolean('active')->default(1);
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
  Schema::dropIfExists('api_clients');
 }
}

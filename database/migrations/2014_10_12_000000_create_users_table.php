<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('user_name')->unique();
            $table->string('photo')->nullable();
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
             $table->string('email_is_verify')->default(0);
            $table->string('password');
            $table->string('country');
            $table->integer('is_admin')->default(0);
            $table->string('is_verify')->nullable();
            $table->string('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
	          $table->string('is_verify')->nullable();
            $table->string('photo')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token')->nullable();
           $table->rememberToken();
            $table->string('api_token')->nullable();

            //role
            $table->biginteger('is_admin')->default(0);
            //order inform
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string(' phone_is_verify')->default(0);
            $table->biginteger('shipping_address')->nullable();
            $table->biginteger('billing_address')->nullable();

            
            $table->text('notes')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->boolean('subscribed_to_news_letter')->default(0);

            
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
        Schema::dropIfExists('users');
    }
}

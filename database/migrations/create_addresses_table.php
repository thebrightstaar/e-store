<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->biginteger('user_id')->unsigned();

            $table->string('street_nomber');
             $table->string('street_name');
             $table->string('city');
             $table->string('sate');
             $table->string('country');
             $table->string('floor_nomber')->nullable();
            $table->string('apartment_nomber')->nullable();

             $table->string('post_code')->nullable();


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
        Schema::dropIfExists('addresses');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
             $table->bigIncrements('id');
             
             $table->biginteger('user_id')->unsigned();
             $table->biginteger('payment_id')->unsigned();
             $table->biginteger('order_id')->unsigned();
             $table-> string('status')->default('pending');
             $table->dateTime('shipment_date')->nullable();
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
        Schema::dropIfExists('shipments');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->biginteger('user_id')->unsigned();
            $table->biginteger('order_id')->unsigned();
            $table->string('titel');
            $table->text('Messages');
            $table->string('status')->default('open');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            string$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
       


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
        Schema::dropIfExists('contact_us');
    }
}

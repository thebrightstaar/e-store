<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizePorductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_porducts', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->string('size');
             $table->biginteger('porduct_id')->unsigned();
             
             $table->foreign('product_id')->references('id')->on('porducts')->onDelete('cascade');

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
        Schema::dropIfExists('size_porducts');
    }
}

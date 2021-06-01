<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColerPorductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coler_porducts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('coler');
            $table->biginteger('quantity');

            $table->biginteger('porduct_id')->unsigned();
            $table->biginteger('size_id')->unsigned();

            $table->timestamps();


            
           
            $table->foreign(['product_id','size_id'])->references(['product_id','id'])->on('size_porducts') ->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coler_porducts');
    }
}

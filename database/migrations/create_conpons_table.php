<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conpons', function (Blueprint $table) {
           
            $table->bigIncrements('id');
            $table->string('code', 32);
            $table->biginteger('user_id')->unsigned();
            $table->biginteger('amount')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->float('percentage')->default(0);
            
            $table->integer('times_used')->default(0);
            $table->integer('usage_limit')->unsigned()->default(0);
            $table->integer('usage_per_customer')->unsigned()->default(0);
            $table->boolean('is_primary')->default(0);

            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conpons');
    }
}

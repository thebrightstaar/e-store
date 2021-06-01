<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 use Kalnoy\Nestedset\NestedSet;

class CreateCatrgoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catrgories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigIncrements('parent_id')unique()->nullable();
            $table->boolean('is_active') ;
             NestedSet::columns($table);
             $table->unsignedInteger('_lft');
             $table->unsignedInteger('_rgt');


             
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
        Schema::dropIfExists('catrgories');
    }
}

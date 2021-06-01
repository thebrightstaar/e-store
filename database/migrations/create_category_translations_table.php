<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 use Kalnoy\Nestedset\NestedSet;

class CreateCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->biginteger('category_id');
            $table->biginteger('category_parent_id');
            $table->string('catrgory_name')->unique();
            $table->string('up_name')->unique();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('category_parent_id')->references('parent_id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_translations');
    }
}

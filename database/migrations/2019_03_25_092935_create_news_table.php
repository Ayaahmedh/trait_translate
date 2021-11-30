<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('core_responding_id')->nullable();
            $table->integer('category_id')->unsigned();
            $table->string('thumb_img', 150);
            $table->date('date');
            $table->timestamps();
        });
        Schema::table('news', function (Blueprint $table) {
            DB::statement('ALTER TABLE ' . $table->getTable() . ' MODIFY COLUMN category_id INT(3)');
            //foreign key constrains
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}

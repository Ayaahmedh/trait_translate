<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model',100)->default('all');
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            //set integers length
            DB::statement('ALTER TABLE ' . $table->getTable() . ' MODIFY COLUMN id INT(3) AUTO_INCREMENT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

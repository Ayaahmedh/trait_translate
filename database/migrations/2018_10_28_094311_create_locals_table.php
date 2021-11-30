<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('local_name',20);
            $table->string('code',2);
            $table->enum('direction',['ltr','rtl']);
            $table->timestamps();
        });
        //set integers length
        Schema::table('locals', function (Blueprint $table) {
            \DB::statement('ALTER TABLE '.$table->getTable().' MODIFY COLUMN id INT(2) AUTO_INCREMENT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locals');
    }
}

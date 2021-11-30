<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('locale_id');
            $table->string('model',255);
            $table->unsignedInteger('model_id')->length(2);
            $table->string('attribute',50);
            $table->text('value');
            $table->timestamps();
        });

        Schema::table('translations', function (Blueprint $table) {
            //set integers length
            DB::statement('ALTER TABLE '.$table->getTable().' MODIFY COLUMN id INT(15) AUTO_INCREMENT');
            DB::statement('ALTER TABLE '.$table->getTable().' MODIFY COLUMN locale_id INT(2)');
            DB::statement('ALTER TABLE '.$table->getTable().' MODIFY COLUMN model_id INT(11)');
            //foreign key constrains
            $table->foreign('locale_id')->references('id')->on('locals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translations');
    }
}

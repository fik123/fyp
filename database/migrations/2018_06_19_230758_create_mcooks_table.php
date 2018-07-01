<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMcooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mcooks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('qty')->default(2);
            $table->string('waiting_time');
            $table->string('cooking_time');
            $table->string('orders')->nullable();
            $table->string('status');
            $table->timestamps();
        });
         Schema::table('mcooks', function (Blueprint $table){
            $table->unsignedInteger('menu_id');
            $table->foreign('menu_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mcooks', function (Blueprint $table){
            $table->dropForeign(['menu_id']);
            $table->dropColumn('menu_id');
        });
        Schema::dropIfExists('mcooks');
    }
}

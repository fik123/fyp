<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('orderno')->nullable();
            $table->string('status');
            $table->string('paid');
            $table->string('takenby')->default('customer');
            $table->timestamps();
        });
        Schema::table('orders', function (Blueprint $table){
            $table->unsignedInteger('table_id');
            $table->foreign('table_id')
                  ->references('id')
                  ->on('tables')
                  ->onDelete('cascade');
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
        Schema::table('orders', function (Blueprint $table){
            $table->dropForeign(['table_id']);
            $table->dropColumn('table_id');
            $table->dropForeign(['menu_id']);
            $table->dropColumn('menu_id');
        });
        Schema::dropIfExists('orders');
    }
}

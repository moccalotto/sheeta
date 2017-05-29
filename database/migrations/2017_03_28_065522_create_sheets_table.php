<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('original_id')->unsigned()->nullable();
            $table->integer('version')->default(1); // optimistic locking.
            $table->string('headline', 60);
            $table->boolean('visible_headline')->default(true);
            $table->boolean('allow_clone')->default(false);
            $table->boolean('allow_view')->default(true);
            $table->integer('clone_count')->default(0);
            $table->integer('clone_level')->default(0);
            $table->mediumText('tables');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('original_id')
                ->references('id')
                ->on('sheets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheets');
    }
}

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
            $table->integer('version')->default(1); // optimistic locking.
            $table->string('headline');
            $table->boolean('allow_copy')->default(false);
            $table->integer('clone_count')->default(0);
            $table->integer('clone_level')->default(0);
            $table->text('tables');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
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

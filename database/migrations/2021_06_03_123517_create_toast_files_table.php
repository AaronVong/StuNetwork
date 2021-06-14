<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToastFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toast_files', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->unsignedBigInteger("toast_id");
            $table->string("name");
            $table->string("url");
            $table->string("type");
            $table->foreign("toast_id")->references("id")->on("toasts")->onDelete("cascade");
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
        Schema::dropIfExists('toast_files');
    }
}

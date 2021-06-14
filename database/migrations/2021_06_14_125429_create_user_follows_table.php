<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follows', function (Blueprint $table) {
            $table->unsignedBigInteger("follower_id")->comment("user_id");
            $table->unsignedBigInteger("following_id")->comment("profile_id");
            $table->primary(["follower_id", "following_id"]);
            $table->foreign("follower_id")->references("id")->on("users");
            $table->foreign("following_id")->references("id")->on("profiles");
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
        Schema::dropIfExists('user_follows');
    }
}

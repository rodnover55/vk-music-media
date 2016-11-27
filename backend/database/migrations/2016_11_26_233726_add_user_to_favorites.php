<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserToFavorites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->integer('user_id');

            $table->dropUnique(['resource_id', 'resource_type']);
            $table->unique(['resource_id', 'resource_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropUnique(['resource_id', 'resource_type', 'user_id']);
            $table->unique(['resource_id', 'resource_type']);
            $table->dropColumn('user_id');
        });
    }
}

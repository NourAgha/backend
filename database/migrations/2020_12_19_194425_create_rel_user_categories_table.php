<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelUserCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rel_user_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->condtrained()
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->condtrained()
                ->onDelete('cascade');
            $table->string('status')->default('Available');
            $table->double('cost')->nullable();
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
        Schema::dropIfExists('rel_user_categories');
    }
}

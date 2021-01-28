<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->integer('type');
            $table->string('desc');
            $table->timestamps();
        });

        DB::table('user_types')->insert([
            'type' => '1',
            'desc' => 'admin'
        ]);
        DB::table('user_types')->insert([
            'type' => '2',
            'desc' => 'delivering_station'
        ]);
        DB::table('user_types')->insert([
            'type' => '3',
            'desc' => 'regular_user'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}

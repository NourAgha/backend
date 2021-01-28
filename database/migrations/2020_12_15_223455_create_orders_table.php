<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->double('amount');
            $table->boolean('paid')->default(false);
            $table->boolean('deliverd')->default(false);
            $table->unsignedBigInteger('deliverd_by')->index('deliverd_by')->nullable();
            $table->unsignedBigInteger('sent_to')->index('sent_to')->nullable();
            $table->string('status')->default('Pending');
            $table->foreignId('user_id')
                ->condtrained()
                ->onDelete('cascade')->index('user_id');
            $table->foreignId('payment_id')
                ->condtrained()
                ->onDelete('cascade')->nullable();
            $table->foreignId('category_id')
                ->condtrained()
                ->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
